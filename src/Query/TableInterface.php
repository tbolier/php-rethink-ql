<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Query;


interface TableInterface
{
    /**
     * @param array $documents
     * @return bool
     */
    public function insert(array $documents): bool;
}