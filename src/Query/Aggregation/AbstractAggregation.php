<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Aggregation;

use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\Query\Transformation\TransformationCompoundInterface;

abstract class AbstractAggregation extends AbstractQuery implements AggregationInterface
{
    /**
     * @inheritdoc
     */
    public function count(): QueryInterface
    {
        return new Count($this->rethink, $this->message, $this);
    }

    /**
     * @inheritdoc
     */
    public function group(string $key): TransformationCompoundInterface
    {
        return new Group($this->rethink, $this->message, $this, $key);
    }

    /**
     * @inheritdoc
     */
    public function ungroup(): TransformationCompoundInterface
    {
        return new Ungroup($this->rethink, $this->message, $this);
    }

    /**
     * @inheritdoc
     */
    public function sum(string $key): QueryInterface
    {
        return new Sum($this->rethink, $this->message, $this, $key);
    }

    /**
     * @inheritdoc
     */
    public function avg(string $key): QueryInterface
    {
        return new Avg($this->rethink, $this->message, $this, $key);
    }

    /**
     * @inheritdoc
     */
    public function min(string $key): AggregationInterface
    {
        return new Min($this->rethink, $this->message, $this, $key);
    }

    /**
     * @inheritdoc
     */
    public function max(string $key): TransformationCompoundInterface
    {
        return new Max($this->rethink, $this->message, $this, $key);
    }
}
