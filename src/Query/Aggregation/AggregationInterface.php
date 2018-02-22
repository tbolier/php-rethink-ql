<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Aggregation;

use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\Response\ResponseInterface;

interface AggregationInterface
{
    /**
     * @return QueryInterface
     */
    public function count(): QueryInterface;

    /**
     * @param string $key
     * @return QueryInterface
     */
    public function sum($key): QueryInterface;

    /**
     * @param string $key
     * @return QueryInterface
     */
    public function avg($key): QueryInterface;

    /**
     * @param string $key
     * @return AggregationInterface
     */
    public function min($key): AggregationInterface;

    /**
     * @param string $key
     * @return AggregationInterface
     */
    public function max($key): AggregationInterface;

    /**
     * @return Iterable|ResponseInterface
     */
    public function run();
}
