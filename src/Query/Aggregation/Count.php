<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query\Aggregation;

use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class Count extends AbstractQuery
{
    /**
     * @var QueryInterface
     */
    private $query;

    public function __construct(RethinkInterface $rethink, QueryInterface $query)
    {
        parent::__construct($rethink);

        $this->query = $query;
        $this->rethink = $rethink;
    }

    public function toArray(): array
    {
        return [
            TermType::COUNT,
            [
                $this->query->toArray(),
            ],
        ];
    }
}
