<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Transformation;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class Limit extends AbstractTransformationCompound
{
    /**
     * @var int
     */
    private $n;

    /**
     * @var QueryInterface
     */
    private $query;

    /**
     * @param RethinkInterface $rethink
     * @param QueryInterface $query
     * @param $n
     */
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

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [
            TermType::LIMIT,
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
