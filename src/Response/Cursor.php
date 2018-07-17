<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Response;

use TBolier\RethinkQL\Connection\ConnectionCursorInterface;
use TBolier\RethinkQL\Connection\ConnectionException;
use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Types\Response\ResponseType;

class Cursor implements \Iterator, \Countable
{
    /**
     * @var ConnectionCursorInterface
     */
    private $connection;
    /**
     * @var int
     */
    private $index;
    /**
     * @var bool
     */
    private $isComplete;
    /**
     * @var MessageInterface
     */
    private $message;
    /**
     * @var ResponseInterface
     */
    private $response;
    /**
     * @var int
     */
    private $size;
    /**
     * @var int
     */
    private $token;

    public function __construct(
        ConnectionCursorInterface $connection,
        int $token,
        ResponseInterface $response,
        MessageInterface $message
    ) {
        $this->connection = $connection;
        $this->token = $token;
        $this->addResponse($response);
        $this->message = $message;
    }

    /**
     * @throws \Exception
     */
    public function current()
    {
        $this->seek();

        if (!$this->valid()) {
            return;
        }

        if ($this->response->isAtomic()) {
            return $this->response->getData();
        }

        return $this->response->getData()[$this->index];
    }

    /**
     * @throws \Exception
     */
    public function next(): void
    {
        $this->index++;

        $this->seek();
    }

    public function key(): int
    {
        return $this->index;
    }

    public function valid(): bool
    {
        return (!$this->isComplete || ($this->index < $this->size));
    }

    /**
     * @throws ConnectionException
     */
    public function rewind(): void
    {
        if ($this->index === 0) {
            return;
        }

        $this->close();
        $this->addResponse($this->connection->rewindFromCursor($this->message));
    }

    public function count()
    {
        return $this->size;
    }

    private function addResponse(ResponseInterface $response)
    {
        $this->index = 0;
        $this->isComplete = $response->getType() === ResponseType::SUCCESS_SEQUENCE;

        if (\is_null($response->getData())) {
            $this->size = 0;
        } else {
            $this->size = $response->isAtomic() ? 1 : \count($response->getData());
        }

        $this->response = $response;
    }

    /**
     * @throws \Exception
     */
    private function seek(): void
    {
        if ($this->index >= $this->size && !$this->isComplete) {
            $this->request();
        }
    }

    /**
     * @throws \Exception
     */
    private function request(): void
    {
        try {
            $response = $this->connection->continueQuery($this->token);
            $this->addResponse($response);
        } catch (\Exception $e) {
            $this->isComplete = true;
            $this->close();

            throw $e;
        }
    }

    private function close(): void
    {
        if (!$this->isComplete) {
            $this->connection->stopQuery($this->token);
            $this->isComplete = true;
        }

        $this->index = 0;
        $this->size = 0;
        $this->response = null;
    }
}
