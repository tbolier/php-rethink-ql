<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Transformation;

use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class IsEmpty extends AbstractQuery
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
            TermType::IS_EMPTY,
            [
                $this->query->toArray(),
            ],
        ];
    }
}
