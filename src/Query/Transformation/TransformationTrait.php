<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Transformation;

use TBolier\RethinkQL\Query\QueryInterface;

trait TransformationTrait
{
    /**
     * @inheritdoc
     */
    public function isEmpty(): QueryInterface
    {
        return new IsEmpty($this->rethink, $this);
    }

    /**
     * @inheritdoc
     */
    public function limit($value): Limit
    {
        return new Limit($this->rethink, $this, $value);
    }

    /**
     * @inheritdoc
     */
    public function skip($value): Skip
    {
        return new Skip($this->rethink, $this, $value);
    }

    /**
     * @inheritdoc
     */
    public function orderBy($key): OrderBy
    {
        return new OrderBy($this->rethink, $this, $key);
    }
}
