<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Query\Operation\OperationInterface;

interface TableInterface extends OperationInterface
{
    /**
     * @param $value
     * @return OperationInterface
     */
    public function get(string $value): OperationInterface;
}
