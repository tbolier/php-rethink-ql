<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Operation;

use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\Query\Aggregation\AggregationTrait;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\Query\Transformation\TransformationTrait;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class Filter extends AbstractQuery
{
    use TransformationTrait;
    use OperationTrait;
    use AggregationTrait;

    /**
     * @var array
     */
    private $predicate;

    /**
     * @var QueryInterface
     */
    private $query;

    /**
     * @param RethinkInterface $rethink
     * @param QueryInterface $query
     * @param array $predicate
     */
    public function __construct(
        RethinkInterface $rethink,
        QueryInterface $query,
        array $predicate
    ) {
        parent::__construct($rethink);

        $this->query = $query;
        $this->predicate = [$predicate];
        $this->rethink = $rethink;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        $jsonDocuments = [];
        foreach ($this->predicate as $key => $document) {
            $jsonDocuments[] = json_encode($document);
        }

        return [
            TermType::FILTER,
            [
                $this->query->toArray(),
                [
                    TermType::JSON,
                    $jsonDocuments,
                ],
            ],
        ];
    }
}
