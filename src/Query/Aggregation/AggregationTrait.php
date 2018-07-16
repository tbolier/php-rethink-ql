<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query\Aggregation;

use TBolier\RethinkQL\Query\QueryInterface;

trait AggregationTrait
{
    /**
     * @inheritdoc
     */
    public function count(): QueryInterface
    {
        return new Count($this->rethink, $this);
    }

    /**
     * @inheritdoc
     */
    public function group(string $key)
    {
        return new Group($this->rethink, $this, $key);
    }

    /**
     * @inheritdoc
     */
    public function ungroup()
    {
        return new Ungroup($this->rethink, $this);
    }

    /**
     * @inheritdoc
     */
    public function sum(string $key): QueryInterface
    {
        return new Sum($this->rethink, $this, $key);
    }

    /**
     * @inheritdoc
     */
    public function avg(string $key): QueryInterface
    {
        return new Avg($this->rethink, $this, $key);
    }

    /**
     * @inheritdoc
     */
    public function min(string $key)
    {
        return new Min($this->rethink, $this, $key);
    }

    /**
     * @inheritdoc
     */
    public function max(string $key)
    {
        return new Max($this->rethink, $this, $key);
    }
}
