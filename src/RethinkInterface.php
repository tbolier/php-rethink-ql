<?php
declare(strict_types=1);

namespace TBolier\RethinkQL;

use TBolier\RethinkQL\Connection\ConnectionInterface;
use TBolier\RethinkQL\Query\Database;
use TBolier\RethinkQL\Query\Ordening;
use TBolier\RethinkQL\Query\Row;
use TBolier\RethinkQL\Query\Table;

interface RethinkInterface
{
    /**
     * @return ConnectionInterface
     */
    public function connection(): ConnectionInterface;

    /**
     * @param string $name
     * @return Table
     */
    public function table(string $name): Table;

    /**
     * @return Database
     */
    public function db(): Database;

    /**
     * @param string $name
     * @return Database
     */
    public function dbCreate(string $name): Database;

    /**
     * @param string $name
     * @return Database
     */
    public function dbDrop(string $name): Database;

    /**
     * @return Database
     */
    public function dbList(): Database;

    /**
     * @param mixed $key
     * @return Ordening
     */
    public function desc($key): Ordening;

    /**
     * @param mixed $key
     * @return Ordening
     */
    public function asc($key): Ordening;

    /**
     * @param string $value
     * @return Row
     */
    public function row(string $value): Row;
}
