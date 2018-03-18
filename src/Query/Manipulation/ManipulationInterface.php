<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Manipulation;

use TBolier\RethinkQL\Query\Aggregation\AggregationInterface;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\Query\Transformation\TransformationCompoundInterface;
use TBolier\RethinkQL\Response\ResponseInterface;

interface ManipulationInterface
{
    /**
     * @param mixed $value
     * @return mixed
     */
    public function lt($value);

    /**
     * @param mixed $value
     * @return mixed
     */
    public function gt($value);
}
