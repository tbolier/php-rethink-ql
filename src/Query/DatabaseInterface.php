<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

interface DatabaseInterface
{
    /**
     * @return self
     */
    public function tableList(): self;

    /**
     * @param string $name
     * @return self
     */
    public function tableCreate(string $name): self;

    /**
     * @param string $name
     * @return self
     */
    public function tableDrop(string $name): self;

    /**
     * @return array
     */
    public function run(): array;
}
