<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Connection;

interface ConnectionInterface
{
    /**
     * @return bool
     */
    public function isConnected(): bool;

    /**
     * @return Connection
     */
    public function connect(): Connection;

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function noReplyWait(): void;

    /**
     * @param array $query
     * @return array
     */
    public function execute(array $query): array;

    /**
     * @param string $name
     * @return void
     */
    public function selectDatabase(string $name): void;

    /**
     * @return string
     */
    public function getSelectedDatabase(): string;

    /**
     * @param bool $noReplyWait
     * @return void
     */
    public function close($noReplyWait = true): void;
}
