<?php

namespace TBolier\RethinkQL\Query;


interface QueryInterface extends \JsonSerializable
{
    /**
     * @return string|array
     */
    public function getQuery();
}
