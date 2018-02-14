<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL;

use TBolier\RethinkQL\Connection\ConnectionInterface;
use TBolier\RethinkQL\Query\DatabaseInterface;
use TBolier\RethinkQL\Query\OrdeningInterface;
use TBolier\RethinkQL\Query\TableInterface;

interface RethinkInterface
{
    /**
     * @return ConnectionInterface
     */
    public function connection(): ConnectionInterface;

    /**
     * @param string $name
     * @return TableInterface
     */
    public function table(string $name): TableInterface;

    /**
     * @return DatabaseInterface
     */
    public function db(): DatabaseInterface;

    /**
     * @param string $name
     * @return DatabaseInterface
     */
    public function dbCreate(string $name): DatabaseInterface;

    /**
     * @param string $name
     * @return DatabaseInterface
     */
    public function dbDrop(string $name): DatabaseInterface;

    /**
     * @return DatabaseInterface
     */
    public function dbList(): DatabaseInterface;

    /**
     * @param string $key
     * @return OrdeningInterface
     */
    public function ordening(string $key): OrdeningInterface;
}
