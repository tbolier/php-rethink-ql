<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Operation;

use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class Changes extends AbstractQuery
{
    /**
     * @var array
     */
    private $options;

    /**
     * @var QueryInterface
     */
    private $query;

    public function __construct(
        RethinkInterface $rethink,
        QueryInterface $query,
        ?array $options
    ) {
        parent::__construct($rethink);

        $this->query = $query;
        $this->rethink = $rethink;
        $this->options = $options;
    }

    public function toArray(): array
    {
        $query = [
            TermType::CHANGES,
            [
                $this->query->toArray(),
            ],
        ];

        if (!empty($this->options)) {
            $query[] = $this->options;
        }

        return $query;
    }
}
