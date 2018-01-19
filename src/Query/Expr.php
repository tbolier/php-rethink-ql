<?php

namespace TBolier\RethinkQL\Query;


class Expr implements QueryInterface
{
    /**
     * @var string
     */
    private $query;

    /**
     * @param string $query
     */
    public function __construct(string $query)
    {
        $this->query = $query;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->query;
    }
}
