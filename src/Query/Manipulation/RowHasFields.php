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

class RowHasFields extends AbstractQuery
{
    /**
     * @var array
     */
    private $keys;

    public function __construct(
        RethinkInterface $rethink,
        array $keys
    ) {
        parent::__construct($rethink);

        $this->keys    = $keys;
        $this->rethink = $rethink;
    }

    public function toArray(): array
    {
        if (\count($this->keys) === 1) {
            $keysQuery = implode($this->keys);
        } else {
            $keysQuery =  [
                TermType::MAKE_ARRAY,
                array_values($this->keys)
            ];
        }

        return [
            TermType::HAS_FIELDS,
            [
                [
                    TermType::IMPLICIT_VAR
                ],
                $keysQuery
            ]
        ];
    }
}
