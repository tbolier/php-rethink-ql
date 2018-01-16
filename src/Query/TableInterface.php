<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Query;

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
     *
     * @return bool
     */
    public function insert(array $documents): bool;

    /**
     * @param array $documents
     *
     * @return bool
     */
    public function update(array $documents): bool;

    /**
     * @param array $documents
     *
     * @return bool
     */
    public function remove(array $documents): bool;
}