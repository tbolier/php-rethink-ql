<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

interface BuilderInterface
{
    /**
     * @param string $name
     * @return TableInterface
     */
    public function table(string $name): TableInterface;

    /**
     * @return DatabaseInterface
     */
    public function database(): DatabaseInterface;

    /**
     * @param string $key
     * @return OrdeningInterface
     */
    public function ordening(string $key): OrdeningInterface;

    /**
     * @param string $value
     * @return RowInterface
     */
    public function row(string $value): RowInterface;
}
