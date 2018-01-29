<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Connection;

use TBolier\RethinkQL\Query\MessageInterface;
use TBolier\RethinkQL\Response\Cursor;
use TBolier\RethinkQL\Response\ResponseInterface;

interface ConnectionInterface
{

    /**
     * @param MessageInterface $query
     * @return void
     */
    public function changes(MessageInterface $query): void;

    /**
     * @param bool $noReplyWait
     * @return void
     */
    public function close($noReplyWait = true): void;

    /**
     * @return Connection
     */
    public function connect(): Connection;

    /**
     * @param string $string
     * @return ResponseInterface
     */
    public function expr(string $string): ResponseInterface;

    /**
     * @param MessageInterface $message
     * @return ResponseInterface
     */
    public function run(MessageInterface $message);

    /**
     * @param MessageInterface $query
     * @return ResponseInterface|Cursor
     */
    public function runNoReply(MessageInterface $query);

    /**
     * @return ResponseInterface
     */
    public function server(): ResponseInterface;

    /**
     * @param string $name
     * @return void
     */
    public function use(string $name): void;
}
