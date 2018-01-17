<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Document;

use TBolier\RethinkQL\Connection\ConnectionInterface;
use TBolier\RethinkQL\Query\BuilderInterface;

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
