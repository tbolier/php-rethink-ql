<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Document;

use TBolier\RethinkConnect\Connection\ConnectionInterface;
use TBolier\RethinkConnect\Query\BuilderInterface;

interface ManagerInterface
{
    /**
     * @return BuilderInterface
     */
    public function createQueryBuilder(): BuilderInterface;

    /**
     * @return ConnectionInterface
     */
    public function getConnection(): ConnectionInterface;

    /**
     * @param string $name
     */
    public function selectDatabase(string $name): void;
}
