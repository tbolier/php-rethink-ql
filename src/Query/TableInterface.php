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
     * @return array
     */
    public function execute(): array;
}