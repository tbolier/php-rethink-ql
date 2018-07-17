<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Transformation;

use TBolier\RethinkQL\Query\QueryInterface;

trait TransformationTrait
{
    public function isEmpty(): QueryInterface
    {
        return new IsEmpty($this->rethink, /** @scrutinizer ignore-type */ $this);
    }

    public function limit($value): Limit
    {
        return new Limit($this->rethink, /** @scrutinizer ignore-type */ $this, $value);
    }

    public function skip($value): Skip
    {
        return new Skip($this->rethink, /** @scrutinizer ignore-type */ $this, $value);
    }

    public function orderBy($key): OrderBy
    {
        return new OrderBy($this->rethink, /** @scrutinizer ignore-type */ $this, $key);
    }
}
