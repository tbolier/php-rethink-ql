<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Query\Operation\OperationInterface;

interface TableInterface extends OperationInterface
{
    /**
     * @param string|int $value
     * @return AbstractQuery
     */
    public function get($value): AbstractQuery;
}
