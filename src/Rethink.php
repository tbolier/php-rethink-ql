<?php
declare(strict_types=1);

namespace TBolier\RethinkQL;

use TBolier\RethinkQL\Connection\ConnectionInterface;
use TBolier\RethinkQL\Query\Builder;
use TBolier\RethinkQL\Query\BuilderInterface;
use TBolier\RethinkQL\Query\DatabaseInterface;
use TBolier\RethinkQL\Query\Message;
use TBolier\RethinkQL\Query\TableInterface;

class Rethink implements RethinkInterface
{
    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * @var BuilderInterface
     */
    private $builder;

    /**
     * @inheritdoc
     */
    public function db(): DatabaseInterface
    {
        return $this->builder->database();
    }

    /**
     * @param string $name
     * @return DatabaseInterface
     */
    public function dbCreate(string $name): DatabaseInterface
    {
        return $this->builder->database()->dbCreate($name);
    }

    /**
     * @param string $name
     * @return DatabaseInterface
     */
    public function dbDrop(string $name): DatabaseInterface
    {
        return $this->builder->database()->dbDrop($name);
    }

    /**
     * @return DatabaseInterface
     */
    public function dbList(): DatabaseInterface
    {
        return $this->builder->database()->dbList();
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
        $this->builder = new Builder($this, new Message());
    }

    /**
     * @inheritdoc
     */
    public function connection(): ConnectionInterface
    {
        return $this->connection;
    }

    /**
     * @inheritdoc
     */
    public function table(string $name): TableInterface
    {
        return $this->builder->table($name);
    }
}
