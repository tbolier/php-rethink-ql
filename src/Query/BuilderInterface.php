<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

interface BuilderInterface
{
    /**
     * @param string $name
     *
     * @return Table
     */
    public function table(string $name): Table;

    /**
     * @return DatabaseInterface
     */
    public function database(): DatabaseInterface;
}
