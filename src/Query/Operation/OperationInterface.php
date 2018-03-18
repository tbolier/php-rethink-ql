<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Operation;

use TBolier\RethinkQL\Query\Aggregation\AggregationInterface;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\Query\Transformation\TransformationCompoundInterface;
use TBolier\RethinkQL\Response\ResponseInterface;

interface OperationInterface extends AggregationInterface
{
    /**
     * @return QueryInterface
     */
    public function delete(): QueryInterface;

    /**
     * @param mixed $predicate
     * @return TransformationCompoundInterface
     */
    public function filter($predicate): TransformationCompoundInterface;

    /**
     * @param int|string|array $keys
     * @return TransformationCompoundInterface
     */
    public function getAll(...$keys): TransformationCompoundInterface;

    /**
     * @param array $elements
     * @return QueryInterface
     */
    public function update(array $elements): QueryInterface;

    /**
     * @param array $document
     * @return QueryInterface
     */
    public function insert(array $document): QueryInterface;

    /**
     * @return Iterable|ResponseInterface
     */
    public function run();
}
