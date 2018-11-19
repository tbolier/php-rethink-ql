<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Response\Cursor;
use TBolier\RethinkQL\Response\ResponseInterface;

interface QueryInterface
{
    /**
     * @return Cursor|ResponseInterface
     */
    public function run();

    public function toArray(): array;
}
