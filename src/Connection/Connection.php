<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Connection;

use Psr\Http\Message\StreamInterface;
use Symfony\Component\Serializer\SerializerInterface;
use TBolier\RethinkQL\Connection\Socket\Exception;
use TBolier\RethinkQL\Connection\Socket\HandshakeInterface;
use TBolier\RethinkQL\Message\ExprMessage;
use TBolier\RethinkQL\Message\Message;
use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Query\Options as QueryOptions;
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
     * @var string
     */
    private $dbName;

    /**
     * @var HandshakeInterface
     */
    private $handshake;

    /**
     * @var bool
     */
    private $noReply = false;

    /**
     * @var SerializerInterface
     */
    private $querySerializer;

    /**
     * @var SerializerInterface
     */
    private $responseSerializer;

    /**
     * @var StreamInterface
     */
    private $stream;

    /**
     * @var \Closure
     */
    private $streamWrapper;

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
     * @throws \Exception
     */
    public function close($noreplyWait = true): void
    {
        if ($noreplyWait) {
            $this->noreplyWait();
        }

        $this->stream->close();
    }

    /**
     * @throws ConnectionException
     */
    public function connect(): Connection
    {
        if ($this->stream !== null && $this->stream->isWritable()) {
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
     * @throws \Exception
     */
    public function reconnect($noreplyWait = true): Connection
    {
        $this->close($noreplyWait);

        return $this->connect();
    }

    /**
     * @throws \Exception
     */
    public function continueQuery(int $token): ResponseInterface
    {
        $message = new Message(QueryType::CONTINUE);
        $this->writeQuery($token, $message);

        // Await the response
        $response = $this->receiveResponse($token, $message);

        if ($response->getType() !== ResponseType::SUCCESS_PARTIAL) {
            unset($this->activeTokens[$token]);
        }

        return $response;
    }

    /**
     * @throws ConnectionException
     */
    public function expr(string $string): ResponseInterface
    {
        $response = $this->run(new ExprMessage(QueryType::START, 'foo'));

        if ($response instanceof ResponseInterface) {
            return $response;
        }

        return new Response();
    }

    /**
     * @throws ConnectionException
     */
    public function rewindFromCursor(MessageInterface $message): ResponseInterface
    {
        return $this->run($message, true);
    }

    /**
     * @throws ConnectionException
     */
    public function run(MessageInterface $message, $raw = false)
    {
        try {
            $token = $this->generateToken();

            $this->writeQuery($token, $message);

            if ($this->noReply) {
                return new Response();
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
     * @throws ConnectionException
     */
    public function runNoReply(MessageInterface $query): void
    {
        $this->noReply = true;
        $this->run($query);
        $this->noReply = false;
    }

    /**
     * @throws \Exception
     */
    public function server(): ResponseInterface
    {
        try {
            $token = $this->generateToken();

            $message = new Message(QueryType::SERVER_INFO);
            $this->writeQuery($token, $message);

            $response = $this->receiveResponse($token, $message);

            if ($response->getType() !== 5) {
                throw new ConnectionException('Unexpected response type for server query.');
            }
        } catch (\Exception $e) {
            throw new ConnectionException($e->getMessage(), $e->getCode(), $e);
        }

        return $response;
    }

    /**
     * @throws \Exception
     */
    public function stopQuery(int $token): ResponseInterface
    {
        $message = new Message(QueryType::STOP);

        $this->writeQuery($token, $message);

        $response = $this->receiveResponse($token, $message);

        unset($this->activeTokens[$token]);

        return $response;
    }

    public function use(string $name): void
    {
        $this->dbName = $name;
    }

    /**
     * @throws \Exception
     */
    public function writeQuery(int $token, MessageInterface $message): int
    {
        if (!$this->stream) {
            throw new ConnectionException('No open stream, please connect first');
        }

        if ($this->dbName) {
            $message->setOptions((new QueryOptions())->setDb($this->dbName));
        }

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
     * @throws ConnectionException
     * @throws \Exception
     */
    public function noreplyWait(): void
    {
        try {
            $token = $this->generateToken();

            $message = new Message(QueryType::NOREPLY_WAIT);
            $this->writeQuery($token, $message);

            $response = $this->receiveResponse($token, $message);

            if ($response->getType() !== 4) {
                throw new ConnectionException('Unexpected response type for noreplyWait query.');
            }
        } catch (\Exception $e) {
            throw new ConnectionException($e->getMessage(), $e->getCode(), $e);
        }
    }

    private function createCursorFromResponse(
        ResponseInterface $response,
        int $token,
        MessageInterface $message
    ): Iterable {
        return new Cursor($this, $token, $response, $message);
    }

    /**
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
     * @throws \RuntimeException
     * @throws ConnectionException
     */
    private function receiveResponse(int $token, MessageInterface $message): ResponseInterface
    {
        $responseHeader = $this->stream->read(4 + 8);
        if (empty($responseHeader)) {
            throw new ConnectionException('Empty response headers received from server.');
        }

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
     * @throws ConnectionException
     */
    private function validateResponse(
        ResponseInterface $response,
        int $responseToken,
        int $token,
        MessageInterface $message
    ): void {
        if ($response->getType() === null) {
            throw new ConnectionException('Response message has no type.');
        }

        if ($response->getType() === ResponseType::CLIENT_ERROR) {
            throw new ConnectionException(
                'Client error: ' . $response->getData()[0] . ' jsonQuery: ' . json_encode($message)
            );
        }

        if ($responseToken !== $token) {
            throw new ConnectionException(
                'Received wrong token. Response does not match the request. '
                . 'Expected ' . $token . ', received ' . $responseToken
            );
        }

        if ($response->getType() === ResponseType::COMPILE_ERROR) {
            throw new ConnectionException(
                'Compile error: ' . $response->getData()[0] . ', jsonQuery: ' . json_encode($message)
            );
        }

        if ($response->getType() === ResponseType::RUNTIME_ERROR) {
            throw new ConnectionException(
                'Runtime error: ' . $response->getData()[0] . ', jsonQuery: ' . json_encode($message)
            );
        }
    }
}
