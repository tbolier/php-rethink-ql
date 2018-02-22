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
    public function sum(string $key): QueryInterface;

    /**
     * @param string $key
     * @return QueryInterface
     */
    public function avg(string $key): QueryInterface;

    /**
     * @param string $key
     * @return AggregationInterface
     */
    public function min(string $key): AggregationInterface;

    /**
     * @param string $key
     * @return AggregationInterface
     */
    public function max(string $key): AggregationInterface;

    /**
     * @return Iterable|ResponseInterface
     */
    public function run();
}
