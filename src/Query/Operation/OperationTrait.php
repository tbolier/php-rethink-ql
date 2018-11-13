<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Operation;

use TBolier\RethinkQL\Query\Row;
use TBolier\RethinkQL\Query\QueryInterface;

trait OperationTrait
{
    public function changes(array $options = null): Changes
    {
        return new Changes($this->rethink, /** @scrutinizer ignore-type */ $this, $options);
    }

    public function delete(): QueryInterface
    {
        return new Delete($this->rethink, /** @scrutinizer ignore-type */ $this);
    }

    public function filter($value)
    {
        if (\is_object($value)) {
            return new FilterByRow($this->rethink, /** @scrutinizer ignore-type */ $this, $value);
        }

        return new Filter($this->rethink, /** @scrutinizer ignore-type */ $this, $value);
    }

    public function getAll(...$keys): GetAll
    {
        return new GetAll($this->rethink, /** @scrutinizer ignore-type */ $this, $keys);
    }

    public function update(array $elements): QueryInterface
    {
        return new Update($this->rethink, /** @scrutinizer ignore-type */ $this, $elements);
    }

    public function insert(array $document): QueryInterface
    {
        return new Insert($this->rethink, /** @scrutinizer ignore-type */ $this, $document);
    }
}
