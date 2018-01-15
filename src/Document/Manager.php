<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Document;

use TBolier\RethinkConnect\Connection\ConnectionInterface;
use TBolier\RethinkConnect\Query\Builder;
use TBolier\RethinkConnect\Query\BuilderInterface;

class Manager implements ManagerInterface
{
    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @inheritdoc
     */
    public function createQueryBuilder(): BuilderInterface
    {
        return new Builder($this);
    }

    /**
     * @inheritdoc
     */
    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }

    /**
     * @inheritdoc
     */
    public function selectDatabase(string $name): void
    {
        $this->connection->selectDatabase($name);
    }
}
