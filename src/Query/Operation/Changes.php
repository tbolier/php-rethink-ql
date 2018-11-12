<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Operation;

use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class Changes extends AbstractQuery
{
    /**
     * @var QueryInterface
     */
    private $query;

    public function __construct(
        RethinkInterface $rethink,
        QueryInterface $query
    ) {
        parent::__construct($rethink);

        $this->query = $query;
        $this->rethink = $rethink;
    }

    // [1,[152,[[15,[[14,["booking"]],"etabs"]]]],{"binary_format":"raw","time_format":"raw","profile":false}]
    // [1,[[1,[152,[[15,["tabletest"]]]]]],{"db":[14,["test"]]}]
    public function toArray(): array
    {
        return [
            TermType::CHANGES,
            [
                $this->query->toArray(),
            ],
        ];
    }
}
