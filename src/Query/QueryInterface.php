<?php

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Response\ResponseInterface;

interface QueryInterface
{
    /**
     * @return \Iterable|ResponseInterface
     */
    public function run();

    /**
     * @return array
     */
    public function toArray(): array;
}
