<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

interface TableInterface
{
    /**
     * @return TableInterface
     */
    public function count(): TableInterface;

    /**
     * @return TableInterface
     */
    public function delete(): TableInterface;

    /**
     * @param array $documents
     * @return TableInterface
     */
    public function filter(array $documents): TableInterface;

    /**
     * @param mixed $value
     * @return TableInterface
     */
    public function get($value): TableInterface;

    /**
     * @param array $documents
     * @return TableInterface
     */
    public function insert(array $documents): TableInterface;

    /**
     * @return mixed
     */
    public function run();

    /**
     * @param array $documents
     * @return TableInterface
     */
    public function update(array $documents): TableInterface;
}
