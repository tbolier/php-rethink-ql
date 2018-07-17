<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Transformation;

use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\Query\Operation\OperationTrait;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class Skip extends AbstractQuery
{
    use TransformationTrait;
    use OperationTrait;

    /**
     * @var int
     */
    private $n;

    /**
     * @var QueryInterface
     */
    private $query;

    public function __construct(
        RethinkInterface $rethink,
        QueryInterface $query,
        int $n
    ) {
        parent::__construct($rethink);

        $this->query = $query;
        $this->n = $n;
        $this->rethink = $rethink;
    }

    public function toArray(): array
    {
        return [
            TermType::SKIP,
            [
                $this->query->toArray(),
                [
                    TermType::DATUM,
                    $this->n,
                ],
            ],
        ];
    }
}
