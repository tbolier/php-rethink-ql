<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Aggregation;

use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\Query\Transformation\AbstractTransformation;

abstract class AbstractAggregation extends AbstractTransformation implements AggregationInterface
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
    public function sum($key): AggregationInterface
    {
        return new Sum($this->rethink, $this->message, $this, $key);
    }

    /**
     * @inheritdoc
     */
    public function avg($key): AggregationInterface
    {
        return new Avg($this->rethink, $this->message, $this, $key);
    }

    /**
     * @inheritdoc
     */
    public function min($key): AggregationInterface
    {
        return new Min($this->rethink, $this->message, $this, $key);
    }

    /**
     * @inheritdoc
     */
    public function max($key): AggregationInterface
    {
        return new Max($this->rethink, $this->message, $this, $key);
    }
}
