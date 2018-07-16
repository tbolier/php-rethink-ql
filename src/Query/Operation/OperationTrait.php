<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Operation;

use TBolier\RethinkQL\Query\Row;
use TBolier\RethinkQL\Query\QueryInterface;

trait OperationTrait
{
    /**
     * @inheritdoc
     */
    public function delete(): QueryInterface
    {
        return new Delete($this->rethink, $this);
    }

    /**
     * @inheritdoc
     */
    public function filter($value)
    {
        if ($value instanceof Row) {
            return new FilterByRow($this->rethink, $this, $value);
        }

        return new Filter($this->rethink, $this, $value);
    }

    /**
     * @inheritdoc
     */
    public function getAll(...$keys): GetAll
    {
        return new GetAll($this->rethink, $this, $keys);
    }

    /**
     * @inheritdoc
     */
    public function update(array $elements): QueryInterface
    {
        return new Update($this->rethink, $this, $elements);
    }

    /**
     * @inheritdoc
     */
    public function insert(array $document): QueryInterface
    {
        return new Insert($this->rethink, $this, $document);
    }
}
