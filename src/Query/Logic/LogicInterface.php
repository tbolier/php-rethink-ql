<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Logic;

use TBolier\RethinkQL\Query\Aggregation\AggregationInterface;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\Query\Transformation\TransformationCompoundInterface;
use TBolier\RethinkQL\Response\ResponseInterface;

interface LogicInterface extends AggregationInterface
{
    /**
     * @param mixed $dateTime
     * @return mixed
     */
    public function lt($dateTime);

    /**
     * @param mixed $dateTime
     * @return mixed
     */
    public function gt($dateTime);
}
