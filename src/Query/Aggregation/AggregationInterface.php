<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Aggregation;

use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\Query\Transformation\TransformationInterface;
use TBolier\RethinkQL\Response\ResponseInterface;

interface AggregationInterface extends TransformationInterface
{
    /**
     * @return QueryInterface
     */
    public function count(): QueryInterface;

    /**
     * @param string $key
     * @return AggregationInterface
     */
    public function sum($key): AggregationInterface;

    /**
     * @param string $key
     * @return AggregationInterface
     */
    public function avg($key): AggregationInterface;

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
