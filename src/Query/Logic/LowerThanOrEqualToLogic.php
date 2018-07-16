<?php

namespace TBolier\RethinkQL\Query\Logic;

use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\Query\Operation\OperationTrait;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class LowerThanOrEqualToLogic extends AbstractQuery
{
    use OperationTrait;

    /**
     * @var QueryInterface
     */
    private $query;

    /**
     * @var string
     */
    private $value;

    public function __construct(
        RethinkInterface $rethink,
        QueryInterface $query,
        $value
    ) {
        parent::__construct($rethink);

        $this->value = $value;
        $this->rethink = $rethink;

        $this->query = $query;
    }

    public function toArray(): array
    {
        return
            [
                TermType::LE,
                [
                    $this->query->toArray(),
                    $this->value,
                ],
            ];
    }
}
