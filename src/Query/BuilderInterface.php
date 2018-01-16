<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Query;

interface BuilderInterface
{
    /**
     * @param string $name
     *
     * @return TableInterface
     */
    public function table(string $name): TableInterface;
}