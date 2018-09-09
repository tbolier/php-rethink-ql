<?php

namespace TBolier\RethinkQL\Query\Manipulation;

use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\Query\Aggregation\AggregationTrait;
use TBolier\RethinkQL\Query\Operation\OperationTrait;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\Query\Transformation\TransformationTrait;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class GetField extends AbstractQuery
{
    use AggregationTrait;
    use OperationTrait;
    use TransformationTrait;

    /**
     * @var string
     */
    private $field;

    /**
     * @var QueryInterface|null
     */
    private $query;

    public function __construct(
        RethinkInterface $rethink,
        string $field,
        ?QueryInterface $query = null
    ) {
        parent::__construct($rethink);

        $this->field = $field;
        $this->rethink = $rethink;

        $this->query = $query;
    }

    public function toArray(): array
    {
        if ($this->query !== null) {
            return [
                TermType::GET_FIELD,
                [
                    $this->query->toArray(),
                    $this->field,
                ],
            ];
        }

        return [
            TermType::GET_FIELD,
            [
                [
                    TermType::IMPLICIT_VAR,
                    [],
                ],
                $this->field,
            ],
        ];
    }
}
