<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Connection;

use Psr\Http\Message\StreamInterface;
use Symfony\Component\Serializer\SerializerInterface;
use TBolier\RethinkQL\Connection\Socket\Exception;
use TBolier\RethinkQL\Connection\Socket\HandshakeInterface;
use TBolier\RethinkQL\Query\Expr;
use TBolier\RethinkQL\Query\Message;
use TBolier\RethinkQL\Query\MessageInterface;
use TBolier\RethinkQL\Query\Options as QueryOptions;
use TBolier\RethinkQL\Query\Query;
use TBolier\RethinkQL\Response\Cursor;
use TBolier\RethinkQL\Response\Response;
use TBolier\RethinkQL\Response\ResponseInterface;
use TBolier\RethinkQL\Types\Query\QueryType;
use TBolier\RethinkQL\Types\Response\ResponseType;

class Connection implements ConnectionInterface, ConnectionCursorInterface
{
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
     * @var SerializerInterface
     */
    private $querySerializer;

    /**
     * @var SerializerInterface
     */
    private $responseSerializer;

    /**
     * @param \Closure $streamWrapper
     * @param HandshakeInterface $handshake
     * @param string $dbName
     * @param SerializerInterface $querySerializer
     * @param SerializerInterface $responseSerializer
     */
    public function __construct(
        \Closure $streamWrapper,
        HandshakeInterface $handshake,
        string $dbName,
        SerializerInterface $querySerializer,
        SerializerInterface $responseSerializer
    ) {
        $this->streamWrapper = $streamWrapper;
        $this->dbName = $dbName;
        $this->handshake = $handshake;
        $this->querySerializer = $querySerializer;
        $this->responseSerializer = $responseSerializer;
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

            if ($response->getType() !== 4) {
                throw new ConnectionException('Unexpected response type for noreplyWait query.');
            }
        } catch (\Exception $e) {
            throw new ConnectionException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param MessageInterface $message
     * @param bool $raw
     * @return ResponseInterface|Cursor
     * @throws ConnectionException
     */
    public function run(MessageInterface $message, $raw = false)
    {
        if (!$this->isStreamOpen()) {
            throw new ConnectionException('Not connected.');
        }

        try {
            $token = $this->generateToken();

            $this->writeQuery($token, $message);

            if ($this->noReply) {
                return;
            }

            $response = $this->receiveResponse($token, $message);

            if ($response->getType() === ResponseType::SUCCESS_PARTIAL) {
                $this->activeTokens[$token] = true;
            }

            if ($raw || $response->getType() === ResponseType::SUCCESS_ATOM) {
                return $response;
            }

            return $this->createCursorFromResponse($response, $token, $message);
        } catch (\Exception $e) {
            throw new ConnectionException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @inheritdoc
     * @throws ConnectionException
     */
    public function rewindFromCursor(MessageInterface $message): ResponseInterface
    {
        return $this->run($message, true);
    }

    /**
     * @param ResponseInterface $response
     * @param int $token
     * @param MessageInterface $message
     * @return Cursor
     */
    private function createCursorFromResponse(ResponseInterface $response, int $token, MessageInterface $message): Cursor
    {
        return new Cursor($this, $token, $response, $message);
    }

    /**
     * @param MessageInterface $query
     * @return ResponseInterface|Cursor
     * @throws ConnectionException
     */
    public function runNoReply(MessageInterface $query)
    {
        $this->noReply = true;
        $result = $this->run($query);
        $this->noReply = false;

        return $result;
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
                $token = random_int(0, $maxToken);
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
     * @inheritdoc
     * @throws \Exception
     */
    public function writeQuery(int $token, MessageInterface $message): int
    {
        $message->setOptions((new QueryOptions())->setDb($this->dbName));

        try {
            $request = $this->querySerializer->serialize($message, 'json');
        } catch (\Exception $e) {
            throw new Exception('Serializing query message failed.', $e->getCode(), $e);
        }

        $requestSize = pack('V', \strlen($request));
        $binaryToken = pack('V', $token) . pack('V', 0);

        return $this->stream->write($binaryToken . $requestSize . $request);
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function continueQuery(int $token): ResponseInterface
    {
        $message = (new Message())->setQuery(
            new Query([QueryType::CONTINUE])
        );

        $this->writeQuery($token, $message);

        // Await the response
        $response = $this->receiveResponse($token, $message);

        if ($response->getType() !== ResponseType::SUCCESS_PARTIAL) {
            unset($this->activeTokens[$token]);
        }

        return $response;
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function stopQuery(int $token): ResponseInterface
    {
        $message = (new Message())->setQuery(
            new Query([QueryType::STOP])
        );

        $this->writeQuery($token, $message);

        $response = $this->receiveResponse($token, $message);

        unset($this->activeTokens[$token]);

        return $response;
    }

    /**
     * @param int $token
     * @param MessageInterface $message
     * @return ResponseInterface
     * @throws \RuntimeException
     * @throws ConnectionException
     */
    private function receiveResponse(int $token, MessageInterface $message): ResponseInterface
    {
        $responseHeader = $this->stream->read(4 + 8);
        $responseHeader = unpack('Vtoken/Vtoken2/Vsize', $responseHeader);
        $responseToken = $responseHeader['token'];
        if ($responseHeader['token2'] !== 0) {
            throw new ConnectionException('Invalid response from server: Invalid token.');
        }

        $responseSize = $responseHeader['size'];
        $responseBuf = $this->stream->read($responseSize);

        /** @var ResponseInterface $response */
        $response = $this->responseSerializer->deserialize($responseBuf, Response::class, 'json');
        $this->validateResponse($response, $responseToken, $token, $message);

        return $response;
    }

    /**
     * @param ResponseInterface $response
     * @param int $responseToken
     * @param int $token
     * @param MessageInterface $message
     * @throws ConnectionException
     */
    private function validateResponse(
        ResponseInterface $response,
        int $responseToken,
        int $token,
        MessageInterface $message
    ): void {
        if (!$response->getType()) {
            throw new ConnectionException('Response message has no type.');
        }

        if ($response->getType() === ResponseType::CLIENT_ERROR) {
            throw new ConnectionException('Server says PHP-RQL is buggy: ' . $response->getData()[0]);
        }

        if ($responseToken !== $token) {
            throw new ConnectionException(
                'Received wrong token. Response does not match the request. '
                . 'Expected ' . $token . ', received ' . $responseToken
            );
        }

        if ($response->getType() === ResponseType::COMPILE_ERROR) {
            throw new ConnectionException('Compile error: ' . $response->getData()[0] . ', jsonQuery: ' . json_encode($message));
        }

        if ($response->getType() === ResponseType::RUNTIME_ERROR) {
            throw new ConnectionException('Runtime error: ' . $response->getData()[0] . ', jsonQuery: ' . json_encode($message));
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
     * @return ResponseInterface
     * @throws \Exception
     */
    public function server(): ResponseInterface
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

            if ($response->getType() !== 5) {
                throw new ConnectionException('Unexpected response type for server query.');
            }
        } catch (\Exception $e) {
            throw new ConnectionException($e->getMessage(), $e->getCode(), $e);
        }

        return $response;
    }

    /**
     * @param string $string
     * @return ResponseInterface
     * @throws ConnectionException
     */
    public function expr(string $string): ResponseInterface
    {
        $message = new Message();
        $message->setQueryType(QueryType::START)
            ->setQuery(new Expr($string));

        return $this->run($message);
    }
}
