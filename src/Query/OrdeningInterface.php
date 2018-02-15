<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query;

interface OrdeningInterface extends QueryInterface
{
    /**
     * @param string $key
     * @return OrdeningInterface
     */
    public function asc(string $key): self;

    /**
     * @param string $key
     * @return OrdeningInterface
     */
    public function desc(string $key): self;
}
