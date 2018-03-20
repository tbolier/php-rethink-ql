<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Transformation;

use TBolier\RethinkQL\Query\Logic\GetFieldLogic;
use TBolier\RethinkQL\Query\Operation\AbstractOperation;
use TBolier\RethinkQL\Query\QueryInterface;

abstract class AbstractTransformationCompound extends AbstractOperation implements TransformationCompoundInterface
{
    /**
     * @param string $field
     * @return TransformationCompoundInterface
     */
    public function getField(string $field): TransformationCompoundInterface
    {
        return new GetFieldLogic($this->rethink, $field, $this);
    }

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
    public function limit($value): TransformationCompoundInterface
    {
        return new Limit($this->rethink, $this, $value);
    }

    /**
     * @inheritdoc
     */
    public function skip($value): TransformationCompoundInterface
    {
        return new Skip($this->rethink, $this, $value);
    }

    /**
     * @inheritdoc
     */
    public function orderBy($key): TransformationCompoundInterface
    {
        return new OrderBy($this->rethink, $this, $key);
    }
}
