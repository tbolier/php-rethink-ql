<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Connection;

use TBolier\RethinkQL\Message\MessageInterface;
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
     * @param bool $noreplyWait
     * @return void
     */
    public function close($noreplyWait = true): void;

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
     * @return void
     */
    public function noreplyWait(): void;

    /**
     * @param bool $noreplyWait
     * @return Connection
     */
    public function reconnect($noreplyWait = true): Connection;

    /**
     * @param MessageInterface $message
     * @return Iterable|ResponseInterface
     */
    public function run(MessageInterface $message);

    /**
     * @param MessageInterface $query
     * @return void
     */
    public function runNoReply(MessageInterface $query): void;

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
