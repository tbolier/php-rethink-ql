<?php
declare(strict_types=1);

namespace TBolier\RethinkQL;

use TBolier\RethinkQL\Connection\ConnectionInterface;
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
}
