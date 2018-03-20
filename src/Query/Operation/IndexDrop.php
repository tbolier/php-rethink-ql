<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Operation;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class IndexDrop extends AbstractQuery
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var QueryInterface
     */
    private $query;

    /**
     * @param RethinkInterface $rethink
     * @param QueryInterface $query
     * @param string $name
     */
    public function __construct(
        RethinkInterface $rethink,
        QueryInterface $query,
        string $name
    ) {
        parent::__construct($rethink);

        $this->query = $query;
        $this->rethink = $rethink;

        $this->name = $name;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [
            TermType::INDEX_DROP,
            [
                $this->query->toArray(),
                [
                    TermType::DATUM,
                    $this->name,
                ],
            ],
        ];
    }
}
