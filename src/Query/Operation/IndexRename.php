<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Operation;

use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class IndexRename extends AbstractQuery
{
    /**
     * @var string
     */
    private $oldValue;

    /**
     * @var string
     */
    private $newValue;

    /**
     * @var QueryInterface
     */
    private $query;

    public function __construct(
        RethinkInterface $rethink,
        QueryInterface $query,
        string $oldValue,
        string $newValue
    ) {
        parent::__construct($rethink);

        $this->query = $query;
        $this->rethink = $rethink;

        $this->oldValue = $oldValue;
        $this->newValue = $newValue;
    }

    public function toArray(): array
    {
        return [
            TermType::INDEX_RENAME,
            [
                $this->query->toArray(),
                [
                    TermType::DATUM,
                    $this->oldValue,
                ],
                [
                    TermType::DATUM,
                    $this->newValue,
                ],
            ],
        ];
    }
}
