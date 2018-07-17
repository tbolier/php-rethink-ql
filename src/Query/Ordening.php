<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class Ordening extends AbstractQuery
{
    /**
     * @var array
     */
    private $query;

    public function __construct(string $key, RethinkInterface $rethink)
    {
        parent::__construct($rethink);

        $this->asc($key);
    }

    public function asc(string $key): Ordening
    {
        $this->query = [
            TermType::ASC,
            [
                $key,
            ],
        ];

        return $this;
    }

    public function desc(string $key): Ordening
    {
        $this->query = [
            TermType::DESC,
            [
                $key,
            ],
        ];

        return $this;
    }

    public function toArray(): array
    {
        return $this->query;
    }
}
