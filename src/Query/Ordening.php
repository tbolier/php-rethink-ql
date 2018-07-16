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

    /**
     * @param string $key
     * @param RethinkInterface $rethink
     */
    public function __construct(string $key, RethinkInterface $rethink)
    {
        parent::__construct($rethink);

        $this->asc($key);
    }

    /**
     * @inheritdoc
     */
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

    /**
     * @inheritdoc
     */
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

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return $this->query;
    }
}
