<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Operation;

use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\Query\Aggregation\AggregationTrait;
use TBolier\RethinkQL\Query\Manipulation\ManipulationTrait;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\Query\Transformation\TransformationTrait;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class GetAll extends AbstractQuery
{
    use AggregationTrait;
    use OperationTrait;
    use TransformationTrait;
    use ManipulationTrait;

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
            TermType::GET_ALL,
            array_merge(
                [$this->query->toArray()],
                array_values($this->keys)
            ),
        ];
    }
}
