<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query\Manipulation;

trait LogicTrait
{
    public function getField(string $field): GetField
    {
        return new GetField($this->rethink, $field, $this->query);
    }
}
