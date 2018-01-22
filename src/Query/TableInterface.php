<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

interface TableInterface
{
    /**
     * @return TableInterface
     */
    public function count(): self;

    /**
     * @param array $documents
     * @return self
     */
    public function filter(array $documents): self;

    /**
     * @param mixed $value
     * @return self
     */
    public function get($value): self;

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
     * @return self
     */
    public function delete(): self;

    /**
     * @return array
     */
    public function run(): array;
}
