<?php

namespace TBolier\RethinkQL\Query\Logic;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\Query\Transformation\AbstractTransformationCompound;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class GetFieldLogic extends AbstractTransformationCompound
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var QueryInterface|null
     */
    private $query;

    /**
     * @param RethinkInterface $rethink
     * @param QueryInterface $query
     * @param string $field
     */
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

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        if ($this->query !== null) {
            return [
                TermType::GET_FIELD,
                [
                    $this->query->toArray(),
                    $this->field
                ],
            ];
        }

        return [
            TermType::GET_FIELD,
            [
                [
                    TermType::IMPLICIT_VAR,
                    []
                ],
                $this->field
            ],
        ];
    }
}
