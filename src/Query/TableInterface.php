<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Query\Operation\OperationInterface;

interface TableInterface extends OperationInterface
{
    /**
     * @param string|int $key
     * @return AbstractQuery
     */
    public function get($key): AbstractQuery;
}
