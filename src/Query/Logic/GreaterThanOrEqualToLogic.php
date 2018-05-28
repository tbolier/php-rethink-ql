<?php

namespace TBolier\RethinkQL\Query\Logic;

use TBolier\RethinkQL\Query\Operation\AbstractOperation;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class GreaterThanOrEqualToLogic extends AbstractOperation
{
    /**
     * @var QueryInterface
     */
    private $query;

    /**
     * @var string
     */
    private $value;

    /**
     * @param RethinkInterface $rethink
     * @param QueryInterface $query
     * @param $value
     */
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

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return
            [
                TermType::GE,
                [
                    $this->query->toArray(),
                    $this->value,
                ],
            ];
    }
}
