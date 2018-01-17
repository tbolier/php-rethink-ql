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
     * @return void
     */
    public function connect();

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function noReplyWait(): void;

    /**
     * @return array
     * @throws Exception
     */
    public function execute(): array;

    /**
     * @param string $name
     * @return void
     */
    public function selectDatabase(string $name): void;

    /**
     * @param string $name
     * @return void
     */
    public function selectTable($name): void;

    /**
     * @param bool $noReplyWait
     * @return void
     */
    public function close($noReplyWait = true): void;
}
