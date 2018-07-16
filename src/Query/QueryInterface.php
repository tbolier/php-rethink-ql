<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Response\ResponseInterface;

interface QueryInterface
{
    /**
     * @return Iterable|ResponseInterface
     */
    public function run();

    public function toArray(): array;
}
