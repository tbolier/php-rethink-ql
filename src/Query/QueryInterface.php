<?php

namespace TBolier\RethinkQL\Query;

interface QueryInterface
{
    /**
     * @return mixed
     */
    public function run();

    /**
     * @return array
     */
    public function toArray(): array;
}
