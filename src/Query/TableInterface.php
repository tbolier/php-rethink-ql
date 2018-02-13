<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Query\Operation\OperationInterface;

interface TableInterface extends OperationInterface
{
    /**
     * @param string|int $value
     * @return QueryInterface
     */
    public function get($value): QueryInterface;

    /**
     * @param int $n
     * @return QueryInterface
     */
    public function limit($n): QueryInterface;

    /**
     * @param string $value
     * @return QueryInterface
     */
    public function orderby($value): QueryInterface;
}
