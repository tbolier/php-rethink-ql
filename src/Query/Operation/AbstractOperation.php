<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Operation;

use TBolier\RethinkQL\Query\Aggregation\AbstractAggregation;
use TBolier\RethinkQL\Query\QueryInterface;

abstract class AbstractOperation extends AbstractAggregation implements OperationInterface
{
    /**
     * @inheritdoc
     */
    public function delete(): QueryInterface
    {
        return new Delete($this->rethink, $this->message, $this);
    }

    /**
     * @inheritdoc
     */
    public function filter(array $predicate): OperationInterface
    {
        return new Filter($this->rethink, $this->message, $this, $predicate);
    }

    /**
     * @inheritdoc
     */
    public function getAll(...$keys): OperationInterface
    {
        return new GetAll($this->rethink, $this->message, $this, $keys);
    }

    /**
     * @inheritdoc
     */
    public function update(array $elements): QueryInterface
    {
        return new Update($this->rethink, $this->message, $this, $elements);
    }

    /**
     * @inheritdoc
     */
    public function insert(array $document): QueryInterface
    {
        return new Insert($this->rethink, $this->message, $this, $document);
    }
}
