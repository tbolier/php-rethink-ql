<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query\Aggregation;

use TBolier\RethinkQL\Query\QueryInterface;

trait AggregationTrait
{
    public function count(): QueryInterface
    {
        return new Count($this->rethink, /** @scrutinizer ignore-type */ $this);
    }

    public function group(string $key)
    {
        return new Group($this->rethink, /** @scrutinizer ignore-type */ $this, $key);
    }

    public function ungroup()
    {
        return new Ungroup($this->rethink, /** @scrutinizer ignore-type */ $this);
    }

    public function sum(string $key): QueryInterface
    {
        return new Sum($this->rethink, /** @scrutinizer ignore-type */ $this, $key);
    }

    public function avg(string $key): QueryInterface
    {
        return new Avg($this->rethink, /** @scrutinizer ignore-type */ $this, $key);
    }

    public function min(string $key)
    {
        return new Min($this->rethink, /** @scrutinizer ignore-type */ $this, $key);
    }

    public function max(string $key)
    {
        return new Max($this->rethink, /** @scrutinizer ignore-type */ $this, $key);
    }
}
