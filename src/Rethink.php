<?php
declare(strict_types=1);

namespace TBolier\RethinkQL;

use TBolier\RethinkQL\Connection\ConnectionInterface;
use TBolier\RethinkQL\Query\Builder;
use TBolier\RethinkQL\Query\Database;
use TBolier\RethinkQL\Query\Ordening;
use TBolier\RethinkQL\Query\Row;
use TBolier\RethinkQL\Query\Table;

class Rethink implements RethinkInterface
{
    /**
     * @var Builder
     */
    private $builder;
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
        $this->builder = new Builder($this);
    }

    /**
     * @inheritdoc
     */
    public function db(): Database
    {
        return $this->builder->database();
    }

    /**
     * @param string $name
     * @return Database
     */
    public function dbCreate(string $name): Database
    {
        return $this->builder->database()->dbCreate($name);
    }

    /**
     * @param string $name
     * @return Database
     */
    public function dbDrop(string $name): Database
    {
        return $this->builder->database()->dbDrop($name);
    }

    /**
     * @return Database
     */
    public function dbList(): Database
    {
        return $this->builder->database()->dbList();
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
    public function table(string $name): Table
    {
        return $this->builder->table($name);
    }

    /**
     * @inheritdoc
     */
    public function desc($key): Ordening
    {
        return $this->builder->ordening($key)->desc($key);
    }

    /**
     * @inheritdoc
     */
    public function asc($key): Ordening
    {
        return $this->builder->ordening($key)->asc($key);
    }

    /**
     * @param string $value
     * @return Row
     */
    public function row(string $value): Row
    {
        return $this->builder->row($value);
    }
}
