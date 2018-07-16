<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Operation;

use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class Update extends AbstractQuery
{
    /**
     * @var array
     */
    private $elements;

    /**
     * @var QueryInterface
     */
    private $query;

    public function __construct(
        RethinkInterface $rethink,
        QueryInterface $query,
        array $elements
    ) {
        parent::__construct($rethink);

        $this->query = $query;
        $this->elements = $elements;
        $this->rethink = $rethink;
    }

    public function toArray(): array
    {
        $jsonElements = json_encode($this->elements);

        return [
            TermType::UPDATE,
            [
                $this->query->toArray(),
                [
                    TermType::JSON,
                    [
                        $jsonElements
                    ],
                ],
            ],
        ];
    }
}
