<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Connection;

interface ConnectionInterface
{
    /**
     * @return bool
     */
    public function isConnected(): bool;

    /**
     * @return void
     */
    public function connect(): void;

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
}
