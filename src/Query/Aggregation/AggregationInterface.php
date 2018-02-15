<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Aggregation;

use TBolier\RethinkQL\Response\ResponseInterface;

interface AggregationInterface
{
    /**
     * @return Iterable|ResponseInterface
     */
    public function run();
}
