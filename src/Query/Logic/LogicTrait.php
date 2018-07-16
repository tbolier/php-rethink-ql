<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query\Logic;

trait LogicTrait
{
    /**
     * @inheritdoc
     */
    public function getField(string $field): GetFieldLogic
    {
        return new GetFieldLogic($this->rethink, $field, $this->query);
    }
}
