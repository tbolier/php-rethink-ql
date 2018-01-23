<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Connection;

use TBolier\RethinkQL\Query\MessageInterface;

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
     * @return array
     */
    public function run(MessageInterface $message): array;

    /**
     * @param MessageInterface $query
     * @return array
     */
    public function runNoReply(MessageInterface $query): array;

    /**
     * @param MessageInterface $query
     * @return array
     */
    public function changes(MessageInterface $query): array;

    /**
     * @return array
     */
    public function server(): array;

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
     * @return array
     */
    public function expr(string $string): array;
}
