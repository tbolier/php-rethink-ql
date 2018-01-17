<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Connection\ConnectionInterface;

interface TableInterface
{
    /**
     * @return int
     */
    public function count(): int;

    /**
     * @param array $documents
     *
     * @return TableInterface
     */
    public function get(array $documents): TableInterface;

    /**
     * @param array $documents
     * @return self
     */
    public function insert(array $documents): self;

    /**
     * @param array $documents
     * @return self
     */
    public function update(array $documents): self;

    /**
     * @param array $documents
     * @return self
     */
    public function remove(array $documents): self;

    /**
     * @param ConnectionInterface $connection
     * @return array
     */
    public function execute(ConnectionInterface $connection): array;
}