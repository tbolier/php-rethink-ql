<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Connection;

use TBolier\RethinkQL\Query\MessageInterface;
use TBolier\RethinkQL\Response\Cursor;
use TBolier\RethinkQL\Response\ResponseInterface;

interface ConnectionInterface
{
    /**
     * @return bool
     */
    public function isStreamOpen(): bool;

    /**
     * @return Connection
     */
    public function connect(): Connection;

    /**
     * @param MessageInterface $message
     * @return ResponseInterface|Cursor
     */
    public function run(MessageInterface $message);

    /**
     * @param MessageInterface $query
     * @return ResponseInterface|Cursor
     */
    public function runNoReply(MessageInterface $query);

    /**
     * @param MessageInterface $query
     * @return array
     */
    public function changes(MessageInterface $query): array;

    /**
     * @return ResponseInterface
     */
    public function server(): ResponseInterface;

    /**
     * @param string $name
     * @return void
     */
    public function use(string $name): void;

    /**
     * @param bool $noReplyWait
     * @return void
     */
    public function close($noReplyWait = true): void;

    /**
     * @param string $string
     * @return ResponseInterface
     */
    public function expr(string $string): ResponseInterface;
}
