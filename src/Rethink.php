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

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
        $this->builder = new Builder($this);
    }

    public function db(): Database
    {
        return $this->builder->database();
    }

    public function dbCreate(string $name): Database
    {
        return $this->builder->database()->dbCreate($name);
    }

    public function dbDrop(string $name): Database
    {
        return $this->builder->database()->dbDrop($name);
    }

    public function dbList(): Database
    {
        return $this->builder->database()->dbList();
    }

    public function connection(): ConnectionInterface
    {
        return $this->connection;
    }

    public function table(string $name): Table
    {
        return $this->builder->table($name);
    }

    public function desc($key): Ordening
    {
        return $this->builder->ordening($key)->desc($key);
    }

    public function asc($key): Ordening
    {
        return $this->builder->ordening($key)->asc($key);
    }

    public function row(?string $value = null): Row
    {
        return $this->builder->row($value);
    }
}
