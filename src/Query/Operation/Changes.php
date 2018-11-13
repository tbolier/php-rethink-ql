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

    // [1,[152,[[15,[[14,["booking"]],"etabs"]]]],{"binary_format":"raw","time_format":"raw","profile":false}]
    // [1,[152,[[15,[[14,["test"]],"tabletest"]]],{"squash":true}],{"binary_format":"raw","time_format":"raw","profile":false}]
    public function toArray(): array
    {
        $query = [
            TermType::CHANGES,
            [
                $this->query->toArray(),
            ],
        ];

        if ($this->options) {
            array_push($query, $this->options);
        }

        return $query;
    }
}
