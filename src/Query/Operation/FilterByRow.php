<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query\Operation;

use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\Query\Manipulation\ManipulationTrait;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\Query\Transformation\TransformationTrait;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class FilterByRow extends AbstractQuery
{
    use TransformationTrait;
    use OperationTrait;
    use ManipulationTrait;

    /**
     * @var QueryInterface
     */
    private $functionQuery;

    /**
     * @var QueryInterface
     */
    private $query;

    public function __construct(
        RethinkInterface $rethink,
        QueryInterface $query,
        QueryInterface $manipulation
    ) {
        parent::__construct($rethink);

        $this->query = $query;
        $this->functionQuery = $manipulation;
        $this->rethink = $rethink;
    }

    public function toArray(): array
    {
        return [
            TermType::FILTER,
            [
                $this->query->toArray(),
                $this->functionQuery->toArray(),
            ],
        ];
    }
}
