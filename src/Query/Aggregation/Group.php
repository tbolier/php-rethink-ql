<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query\Aggregation;

use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\Query\Operation\OperationTrait;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\Query\Transformation\TransformationTrait;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class Group extends AbstractQuery
{
    use TransformationTrait;
    use OperationTrait;
    use AggregationTrait;

    /**
     * @var string
     */
    private $key;

    /**
     * @var QueryInterface
     */
    private $query;

    public function __construct(
        RethinkInterface $rethink,
        QueryInterface $query,
        string $key
    ) {
        parent::__construct($rethink);

        $this->query = $query;
        $this->key = $key;
        $this->rethink = $rethink;
    }

    public function toArray(): array
    {
        return [
            TermType::GROUP,
            [
                $this->query->toArray(),
                $this->key,
            ],
        ];
    }
}
