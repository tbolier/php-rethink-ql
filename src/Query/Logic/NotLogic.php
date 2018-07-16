<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query\Logic;

use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\Query\Operation\OperationTrait;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class NotLogic extends AbstractQuery
{
    use OperationTrait;

    /**
     * @var QueryInterface
     */
    private $query;
    
    public function __construct(
        RethinkInterface $rethink,
        QueryInterface $query
    ) {
        parent::__construct($rethink);

        $this->rethink = $rethink;
        $this->query = $query;
    }

    public function toArray(): array
    {
        return
            [
                TermType::NOT,
                [
                    $this->query->toArray(),
                ],
            ];
    }
}
