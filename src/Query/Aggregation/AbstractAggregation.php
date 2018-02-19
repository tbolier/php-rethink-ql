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
    public function isEmpty(): QueryInterface
    {
        return new IsEmpty($this->rethink, $this->message, $this);
    }

    /**
     * @inheritdoc
     */
    public function limit($value): AggregationInterface
    {
        return new Limit($this->rethink, $this->message, $this, $value);
    }

    /**
     * @inheritdoc
     */
    public function skip($value): AggregationInterface
    {
        return new Skip($this->rethink, $this->message, $this, $value);
    }

    /**
     * @inheritdoc
     */
    public function orderBy($key): AggregationInterface
    {
        return new OrderBy($this->rethink, $this->message, $this, $key);
    }
}
