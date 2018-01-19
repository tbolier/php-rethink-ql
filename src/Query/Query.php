<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;


class Query implements QueryInterface
{
    /**
     * @var array
     */
    private $query;

    /**
     * @param array $query
     */
    public function __construct(array $query)
    {
        $this->query = $query;
    }

    /**
     * @return array
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->query;
    }
}
