<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Response\ResponseInterface;

interface TableInterface
{
    /**
     * @return TableInterface
     */
    public function count(): TableInterface;

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
     * @param array $documents
     * @return TableInterface
     */
    public function update(array $documents): TableInterface;

    /**
     * @return TableInterface
     */
    public function delete(): TableInterface;

    /**
     * @return ResponseInterface
     */
    public function run(): ResponseInterface;
}
