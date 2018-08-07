<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Manipulation;

use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\Query\Aggregation\AggregationTrait;
use TBolier\RethinkQL\Query\Operation\OperationTrait;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\Query\Transformation\TransformationTrait;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class Pluck extends AbstractQuery
{
    use AggregationTrait;
    use OperationTrait;
    use TransformationTrait;

    /**
     * @var array
     */
    private $keys;

    /**
     * @var QueryInterface
     */
    private $query;

    public function __construct(
        RethinkInterface $rethink,
        QueryInterface $query,
        array $keys
    ) {
        parent::__construct($rethink);

        $this->query   = $query;
        $this->keys    = $keys;
        $this->rethink = $rethink;
    }

    public function toArray(): array
    {
        return [
            TermType::PLUCK,
            array_merge(
                [$this->query->toArray()],
                array_values($this->keys)
            ),
        ];
    }
}
