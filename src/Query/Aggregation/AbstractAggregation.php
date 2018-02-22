<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Aggregation;

use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\Query\QueryInterface;

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
    public function sum($key): QueryInterface
    {
        return new Sum($this->rethink, $this->message, $this, $key);
    }

    /**
     * @inheritdoc
     */
    public function avg($key): QueryInterface
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
