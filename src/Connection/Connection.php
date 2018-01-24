<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Connection;

use Psr\Http\Message\StreamInterface;
use TBolier\RethinkQL\Connection\Socket\HandshakeInterface;
use TBolier\RethinkQL\Connection\Socket\StreamHandlerInterface;
use TBolier\RethinkQL\Query\Cursor;
use TBolier\RethinkQL\Query\Expr;
use TBolier\RethinkQL\Query\Message;
use TBolier\RethinkQL\Query\MessageInterface;
use TBolier\RethinkQL\Query\Query;
use TBolier\RethinkQL\Types\Query\QueryType;
use TBolier\RethinkQL\Types\Response\ResponseType;
use TBolier\RethinkQL\Types\Term\TermType;

class Connection implements ConnectionInterface
{
    /**
     * @var OptionsInterface
     */
    private $options;

    /**
     * @var int[]
     */
    private $activeTokens;

    /**
     * @var StreamInterface
     */
    private $stream;

    /**
     * @var string
     */
    private $dbName;

    /**
     * @var bool
     */
    private $noReply = false;

    /**
     * @var \Closure
     */
    private $streamWrapper;

    /**
     * @var HandshakeInterface
     */
    private $handshake;

    /**
     * @param \Closure $streamWrapper
     * @param HandshakeInterface $handshake
     * @param string $dbName
     */
    public function __construct(\Closure $streamWrapper, HandshakeInterface $handshake, string $dbName)
    {
        $this->streamWrapper = $streamWrapper;
        $this->dbName = $dbName;
        $this->handshake = $handshake;
    }

    /**
     * @inheritdoc
     * @throws ConnectionException
     */
    public function connect(): self
    {
        if ($this->isStreamOpen()) {
            return $this;
        }

        try {
            $this->stream = ($this->streamWrapper)();
            $this->handshake->hello($this->stream);
        } catch (\Exception $e) {
            throw new ConnectionException($e->getMessage(), $e->getCode(), $e);
        }

        return $this;
    }

    /**
     * @param bool $noReplyWait
     * @throws ConnectionException
     * @throws \Exception
     */
    public function close($noReplyWait = true): void
    {
        if (!$this->isStreamOpen()) {
            throw new ConnectionException('Not connected.');
        }

        if ($noReplyWait) {
            $this->noReplyWait();
        }

        $this->stream->close();
    }

    /**
     * @throws ConnectionException
     * @throws \Exception
     */
    private function noReplyWait(): void
    {
        if (!$this->isStreamOpen()) {
            throw new ConnectionException('Not connected.');
        }

        try {
            $token = $this->generateToken();

            $query = new Message(QueryType::NOREPLY_WAIT);
            $this->writeQuery($token, $query);

            // Await the response
            $response = $this->receiveResponse($token, $query);

            if ($response['t'] !== 4) {
                throw new ConnectionException('Unexpected response type for noreplyWait query.');
            }
        } catch (\Exception $e) {
            throw new ConnectionException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param MessageInterface $message
     * @return array
     * @throws ConnectionException
     */
    public function run(MessageInterface $message): array
    {
        if (!$this->isStreamOpen()) {
            throw new ConnectionException('Not connected.');
        }

        try {
            $token = $this->generateToken();

            if ($message instanceof Query) {
                $message->setQuery($this->utf8Converter($message->getQuery()));
            }

            $this->writeQuery($token, $message);

            if ($this->noReply) {
                return [];
            }

            // Await the response
            $response = $this->receiveResponse($token, $message);

            if ($response['t'] === ResponseType::SUCCESS_PARTIAL) {
                $this->activeTokens[$token] = true;
            }


            return $response['r'];
        } catch (\Exception $e) {
            throw new ConnectionException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param MessageInterface $query
     * @return array
     * @throws ConnectionException
     */
    public function runNoReply(MessageInterface $query): array
    {
        $this->noReply = true;
        $this->run($query);
        $this->noReply = false;
    }

    /**
     * @param MessageInterface $message
     * @return mixed
     */
    private function utf8Converter(MessageInterface $message): MessageInterface
    {
        if (null !== $message->getQuery()) {
            return $message;
        }

        if (\is_array($message->getQuery()->getQuery())) {
            array_walk_recursive($message->getQuery()->getQuery(), function (&$item) {
                if (is_scalar($item) && !mb_detect_encoding((string)$item, 'utf-8', true)) {
                    $item = utf8_encode($item);
                }
            });

            return $message;
        }

        $scalar = &$message->getQuery()->getQuery();
        if (\is_string($scalar)) {
            utf8_encode($scalar);
        }

        return $message;
    }

    /**
     * @return int
     * @throws \Exception
     */
    private function generateToken(): int
    {
        try {
            $tries = 0;
            $maxToken = 1 << 30;
            do {
                $token = \random_int(0, $maxToken);
                $haveCollision = isset($this->activeTokens[$token]);
            } while ($haveCollision && $tries++ < 1024);
            if ($haveCollision) {
                throw new \Exception('Unable to generate a unique token for the query.');
            }
        } catch (\Exception $e) {
            throw new ConnectionException('Generating the token failed.', $e->getCode(), $e);
        }

        return $token;
    }

    /**
     * @param int $token
     * @param MessageInterface $message
     * @return int
     * @throws \Exception
     */
    private function writeQuery(int $token, MessageInterface $message): int
    {
        $message->setOptions([
            'db' => [
                TermType::DB,
                [$this->dbName],
                (object)[],
            ],
        ]);

        $request = json_encode($message);

        switch (json_last_error()) {
            case JSON_ERROR_DEPTH:
                throw new ConnectionException('JSON error: Maximum stack depth exceeded');
            case JSON_ERROR_STATE_MISMATCH:
                throw new ConnectionException('JSON error: Underflow or the modes mismatch');
            case JSON_ERROR_CTRL_CHAR:
                throw new ConnectionException('JSON error: Unexpected control character found');
            case JSON_ERROR_SYNTAX:
                throw new ConnectionException('JSON error: Syntax error, malformed JSON.');
            case JSON_ERROR_UTF8:
                throw new ConnectionException('JSON error: Malformed UTF-8 characters, possibly incorrectly encoded.');
            case JSON_ERROR_NONE:
                break;
            default:
                throw new ConnectionException('Failed to encode query as JSON: ' . json_last_error());
        }

        if ($request === false) {
            throw new ConnectionException('Failed to encode query as JSON: ' . json_last_error());
        }

        $requestSize = pack('V', \strlen($request));
        $binaryToken = pack('V', $token) . pack('V', 0);

        return $this->stream->write($binaryToken . $requestSize . $request);
    }

    /**
     * @param int $token
     * @param MessageInterface $message
     * @return array
     * @throws \RuntimeException
     * @throws ConnectionException
     */
    private function receiveResponse(int $token, MessageInterface $message): array
    {
        $responseHeader = $this->stream->read(4 + 8);
        $responseHeader = unpack('Vtoken/Vtoken2/Vsize', $responseHeader);
        $responseToken = $responseHeader['token'];
        if ($responseHeader['token2'] !== 0) {
            throw new ConnectionException('Invalid response from server: Invalid token.');
        }

        $responseSize = $responseHeader['size'];
        $responseBuf = $this->stream->read($responseSize);

        $response = json_decode($responseBuf, true);
        $this->validateResponse($response, $responseToken, $token, $message);

        return $response;
    }

    /**
     * @param array $response
     * @param int $responseToken
     * @param int $token
     * @param MessageInterface $message
     * @throws ConnectionException
     */
    private function validateResponse(array $response, int $responseToken, int $token, MessageInterface $message): void
    {
        if (isset($response['error'])) {
            throw new ConnectionException($response['error'], $response['error_code']);
        }

        if (!isset($response['t'])) {
            throw new ConnectionException('Response message has no type.');
        }

        if ($response['t'] === ResponseType::CLIENT_ERROR) {
            throw new ConnectionException('Server says PHP-RQL is buggy: ' . $response['r'][0]);
        }

        if ($responseToken !== $token) {
            throw new ConnectionException(
                'Received wrong token. Response does not match the request. '
                . 'Expected ' . $token . ', received ' . $responseToken
            );
        }

        if ($response['t'] === ResponseType::COMPILE_ERROR) {
            throw new ConnectionException('Compile error: ' . $response['r'][0] . ', jsonQuery: ' . json_encode($message));
        }

        if ($response['t'] === ResponseType::RUNTIME_ERROR) {
            throw new ConnectionException('Runtime error: ' . $response['r'][0] . ', jsonQuery: ' . json_encode($message));
        }
    }

    /**
     * @inheritdoc
     */
    public function use(string $name): void
    {
        $this->dbName = $name;
    }

    /**
     * @inheritdoc
     */
    public function isStreamOpen(): bool
    {
        return ($this->stream !== null && $this->stream->isWritable());
    }

    /**
     * @param MessageInterface $query
     * @return array
     */
    public function changes(MessageInterface $query): array
    {
        // TODO: Implement changes() method.
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function server(): array
    {
        if (!$this->isStreamOpen()) {
            throw new ConnectionException('Not connected.');
        }

        try {
            $token = $this->generateToken();

            $query = new Message(QueryType::SERVER_INFO);
            $this->writeQuery($token, $query);

            // Await the response
            $response = $this->receiveResponse($token, $query);

            if ($response['t'] !== 5) {
                throw new ConnectionException('Unexpected response type for server query.');
            }
        } catch (\Exception $e) {
            throw new ConnectionException($e->getMessage(), $e->getCode(), $e);
        }

        return $response;
    }

    /**
     * @param string $string
     * @return array
     * @throws ConnectionException
     */
    public function expr(string $string): array
    {
        $message = new Message();
        $message->setQueryType(QueryType::START)
            ->setQuery(new Expr($string));

        return $this->run($message);
    }
}
