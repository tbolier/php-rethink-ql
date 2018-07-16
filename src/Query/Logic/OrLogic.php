<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query\Logic;

use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\Query\Operation\OperationTrait;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class OrLogic extends AbstractQuery
{
    use OperationTrait;

    /**
     * @var QueryInterface
     */
    private $functionOne;

    /**
     * @var QueryInterface
     */
    private $functionTwo;

    public function __construct(
        RethinkInterface $rethink,
        QueryInterface $functionOne,
        QueryInterface $functionTwo
    ) {
        parent::__construct($rethink);

        $this->rethink = $rethink;

        $this->functionOne = $functionOne;
        $this->functionTwo = $functionTwo;
    }

    public function toArray(): array
    {
        return
            [
                TermType::OR,
                [
                    $this->functionOne->toArray(),
                    $this->functionTwo->toArray(),
                ],
            ];
    }
}
