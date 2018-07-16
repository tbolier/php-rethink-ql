<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Operation;

use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class TableDrop extends AbstractQuery
{
    /**
     * @var string
     */
    private $name;

    public function __construct(RethinkInterface $rethink, string $name)
    {
        parent::__construct($rethink);

        $this->rethink = $rethink;
        
        $this->name = $name;
    }

    public function toArray(): array
    {
        return [
            TermType::TABLE_DROP,
            [
                [
                    TermType::DATUM,
                    $this->name,
                ],
            ],
        ];
    }
}
