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
    public function connection(): ConnectionInterface;

    public function table(string $name): Table;

    public function db(): Database;

    public function dbCreate(string $name): Database;

    public function dbDrop(string $name): Database;

    public function dbList(): Database;

    public function desc($key): Ordening;

    public function asc($key): Ordening;

    public function row(?string $value = null): Row;
}
