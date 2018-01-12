<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Connection;

interface ConnectionInterface
{
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
