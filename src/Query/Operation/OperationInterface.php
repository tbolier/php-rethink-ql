<?php

namespace TBolier\RethinkQL\Query\Operation;

use TBolier\RethinkQL\Query\QueryInterface;

interface OperationInterface
{
    /**
     * @return QueryInterface
     */
    public function count(): QueryInterface;

    /**
     * @return QueryInterface
     */
    public function delete(): QueryInterface;

    /**
     * @param array $predicate
     * @return OperationInterface
     */
    public function filter(array $predicate): OperationInterface;

    /**
     * @param array $document
     * @return QueryInterface
     */
    public function update(array $document): QueryInterface;

    /**
     * @param array $document
     * @return QueryInterface
     */
    public function insert(array $document): QueryInterface;
}
