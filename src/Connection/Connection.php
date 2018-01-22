<?php
/**
 * @license http://www.apache.org/licenses/ Apache License 2.0
 * @license https://github.com/danielmewes/php-rql Apache License 2.0
 * @Author Daniel Mewes https://github.com/danielmewes
 * @Author Timon Bolier https://github.com/tbolier
 *
 * The Connection class contains parts of code copied from the original PHP-RQL library under the Apache License 2.0:
 * @see https://github.com/danielmewes/php-rql/blob/master/rdb/Connection.php
 *
 * Stating the following changes have been done to parts of code copied in the Connection Class:
 * - Amendments to code styles and control structures.
 * - Abstraction of code to new methods to improve readability.
 * - Improved implementation and removed obsolete code.
 */
declare(strict_types=1);

namespace TBolier\RethinkQL\Connection;


use TBolier\RethinkQL\Query\Expr;
use TBolier\RethinkQL\Query\Message;
use TBolier\RethinkQL\Query\MessageInterface;
use TBolier\RethinkQL\Query\Query;
use TBolier\RethinkQL\Types\Query\QueryType;
use TBolier\RethinkQL\Types\Response\ResponseType;
use TBolier\RethinkQL\Types\Term\TermType;
use TBolier\RethinkQL\Types\VersionDummy\Version;


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
     * @var bool|resource
     */
    private $socket;

    /**
     * @var string
     */
    private $dbname;

    /**
     * @var bool
     */
    private $noReply = false;

    /**
     * @param OptionsInterface $options
     */
    public function __construct(OptionsInterface $options)
    {
        $this->options = $options;
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function connect(): self
    {
        if ($this->isConnected()) {
            return $this;
        }

        try {
            $this->socket = stream_socket_client(
                ($this->options->isSsl() ? 'ssl' : 'tcp') . '://' . $this->options->getHostname() . ':' . $this->options->getPort(),
                $errno,
                $errstr,
                $this->options->getTimeout(),
                STREAM_CLIENT_CONNECT
            );

            stream_set_timeout($this->socket, $this->options->getTimeoutStream());

            // Let's shake hands.
            $handshake = new Handshake($this->options->getUser(), $this->options->getPassword(), Version::V1_0);

            try {
                $handshakeResponse = null;
                while (true) {
                    if (!$this->isConnected()) {
                        throw new Exception('Not connected');
                    }

                    if ($handshakeResponse !== null && preg_match('/^ERROR:\s(.+)$/i', $handshakeResponse,
                            $errorMatch)) {
                        throw new Exception($errorMatch[1]);
                    }

                    try {
                        $msg = $handshake->nextMessage($handshakeResponse);
                    } catch (Exception $e) {
                        $this->close(false);
                        throw $e;
                    }

                    if ($msg === 'successful') {
                        break;
                    }

                    if ($msg !== '') {
                        $this->sendStr($msg);
                    }

                    // Read null-terminated response
                    $handshakeResponse = $this->receiveStr();
                }
            } catch (Exception $e) {
                $this->close(false);
                throw $e;
            }
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }

        if ($this->options->hasDefaultDatabase()) {
            $this->dbname = $this->options->getDbname();
        }

        return $this;
    }

    /**
     * @param bool $noReplyWait
     * @throws Exception
     * @throws \Exception
     */
    public function close($noReplyWait = true): void
    {
        if (!$this->isConnected()) {
            throw new Exception('Not connected.');
        }

        if ($noReplyWait) {
            $this->noReplyWait();
        }

        fclose($this->socket);
        $this->socket = null;
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    private function noReplyWait(): void
    {
        if (!$this->isConnected()) {
            throw new Exception('Not connected.');
        }

        try {
            $token = $this->generateToken();

            $query = new Message(QueryType::NOREPLY_WAIT);
            $this->sendQuery($token, $query);

            // Await the response
            $response = $this->receiveResponse($token, $query);

            if ($response['t'] !== 4) {
                throw new Exception('Unexpected response type for noreplyWait query.');
            }
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param MessageInterface $message
     * @return array
     * @throws Exception
     */
    public function run(MessageInterface $message): array
    {
        if (!$this->isConnected()) {
            throw new Exception('Not connected.');
        }

        try {
            $token = $this->generateToken();

            if ($message instanceof Query) {
                $message->setQuery($this->utf8Converter($message->getQuery()));
            }

            $this->sendQuery($token, $message);

            if ($this->noReply) {
                return [];
            }

            // Await the response
            $response = $this->receiveResponse($token, $message);

            // Todo: support all response types, and decide what the return type should be.
            if ($response['t'] === ResponseType::SUCCESS_PARTIAL) {
                $this->activeTokens[$token] = true;
            }

            return $response['r'];
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param MessageInterface $query
     * @return array
     * @throws Exception
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

        array_walk_recursive($message->getQuery(), function (&$item) {
            if (is_scalar($item) && !mb_detect_encoding((string)$item, 'utf-8', true)) {
                $item = utf8_encode($item);
            }
        });

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
            throw new Exception('Generating the token failed.', $e->getCode(), $e);
        }

        return $token;
    }

    /**
     * @param int $token
     * @param MessageInterface $message
     * @return int
     * @throws \Exception
     */
    private function sendQuery(int $token, MessageInterface $message): int
    {
        $message->setOptions([
            'db' => [
                TermType::DB,
                [$this->dbname],
                (object)[],
            ],
        ]);

        $request = json_encode($message);

        switch (json_last_error()) {
            case JSON_ERROR_DEPTH:
                throw new Exception('JSON error: Maximum stack depth exceeded');
            case JSON_ERROR_STATE_MISMATCH:
                throw new Exception('JSON error: Underflow or the modes mismatch');
            case JSON_ERROR_CTRL_CHAR:
                throw new Exception('JSON error: Unexpected control character found');
            case JSON_ERROR_SYNTAX:
                throw new Exception('JSON error: Syntax error, malformed JSON.');
            case JSON_ERROR_UTF8:
                throw new Exception('JSON error: Malformed UTF-8 characters, possibly incorrectly encoded.');
            case JSON_ERROR_NONE:
                break;
            default:
                throw new Exception('Failed to encode query as JSON: ' . json_last_error());
                break;
        }

        if ($request === false) {
            throw new Exception('Failed to encode query as JSON: ' . json_last_error());
        }

        $requestSize = pack('V', \strlen($request));
        $binaryToken = pack('V', $token) . pack('V', 0);

        return $this->sendStr($binaryToken . $requestSize . $request);
    }

    /**
     * @param string $s
     * @return int
     * @throws Exception
     * @throws \Exception
     */
    private function sendStr(string $s): int
    {
        $bytesWritten = 0;
        while ($bytesWritten < \strlen($s)) {
            $result = fwrite($this->socket, substr($s, $bytesWritten));
            if ($result === false || $result === 0) {
                $metaData = stream_get_meta_data($this->socket);
                $this->close(false);
                if ($metaData['timed_out']) {
                    throw new Exception(
                        'Timed out while writing to socket. Disconnected. '
                        . 'Call setTimeout(seconds) on the connection to change '
                        . 'the timeout.'
                    );
                }
                throw new Exception('Unable to write to socket. Disconnected.');
            }
            $bytesWritten += $result;
        }

        return $bytesWritten;
    }

    /**
     * @param int $length
     * @return string
     * @throws Exception
     */
    private function receiveStr(int $length = -1): string
    {
        $s = '';

        if ($length === -1) {
            while (true) {
                $char = $this->getContent(1);

                // skip initial null-terminated byte
                if ($s === '' && $char === \chr(0)) {
                    continue;
                }

                // reach a null-terminated byte, stop the stream.
                if ($char === '' || $char === \chr(0)) {
                    break;
                }

                $s .= $char;
            }

            return $s;
        }

        while (\strlen($s) < $length) {
            $s .= $this->getContent($length - \strlen($s));
        }

        return $s;
    }

    /**
     * @param $length
     * @return string
     * @throws Exception
     */
    private function getContent($length): string
    {
        $string = stream_get_contents($this->socket, $length);
        if ($string === false || feof($this->socket)) {
            $metaData = stream_get_meta_data($this->socket);
            if ($metaData['timed_out']) {
                throw new Exception(
                    'Timed out while reading from socket. Disconnected. '
                    . 'Call setTimeout(seconds) on the connection to change '
                    . 'the timeout.'
                );
            }
        }

        return $string;
    }

    /**
     * @param int $token
     * @param MessageInterface $message
     * @return array
     * @throws Exception
     */
    private function receiveResponse(int $token, MessageInterface $message): array
    {
        $responseHeader = $this->receiveStr(4 + 8);
        $responseHeader = unpack('Vtoken/Vtoken2/Vsize', $responseHeader);
        $responseToken = $responseHeader['token'];
        if ($responseHeader['token2'] !== 0) {
            throw new Exception('Invalid response from server: Invalid token.');
        }

        $responseSize = $responseHeader['size'];
        $responseBuf = $this->receiveStr($responseSize);

        $response = json_decode($responseBuf, true);
        $this->validateResponse($response, $responseToken, $token, $message);

        return $response;
    }

    /**
     * @param array $response
     * @param int $responseToken
     * @param int $token
     * @param MessageInterface $message
     * @throws Exception
     */
    private function validateResponse(array $response, int $responseToken, int $token, MessageInterface $message): void
    {
        if (isset($response['error'])) {
            throw new Exception($response['error'], $response['error_code']);
        }

        if (!isset($response['t'])) {
            throw new Exception('Response message has no type.');
        }

        if ($response['t'] === ResponseType::CLIENT_ERROR) {
            throw new Exception('Server says PHP-RQL is buggy: ' . $response['r'][0]);
        }

        if ($responseToken !== $token) {
            throw new Exception(
                'Received wrong token. Response does not match the request. '
                . 'Expected ' . $token . ', received ' . $responseToken
            );
        }

        if ($response['t'] === ResponseType::COMPILE_ERROR) {
            throw new Exception('Compile error: ' . $response['r'][0] . ', jsonQuery: ' . json_encode($message));
        }

        if ($response['t'] === ResponseType::RUNTIME_ERROR) {
            throw new Exception('Runtime error: ' . $response['r'][0] . ', jsonQuery: ' . json_encode($message));
        }
    }

    /**
     * @inheritdoc
     */
    public function use(string $name): void
    {
        $this->dbname = $name;
    }

    /**
     * @inheritdoc
     */
    public function isConnected(): bool
    {
        return $this->socket ? true : false;
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
        if (!$this->isConnected()) {
            throw new Exception('Not connected.');
        }

        try {
            $token = $this->generateToken();

            $query = new Message(QueryType::SERVER_INFO);
            $this->sendQuery($token, $query);

            // Await the response
            $response = $this->receiveResponse($token, $query);

            if ($response['t'] !== 5) {
                throw new Exception('Unexpected response type for server query.');
            }
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }

        return $response;
    }

    /**
     * @param string $string
     * @return array
     * @throws Exception
     */
    public function expr(string $string): array
    {
        $message = new Message();
        $message->setQueryType(QueryType::START)
            ->setQuery(new Expr($string));

        return $this->run($message);
    }
}
