<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Types\Query;

class QueryType
{
    /**
     * @var int
     */
    public const START = 1;

    /**
     * @var int
     */
    public const CONTINUE = 2;

    /**
     * @var int
     */
    public const STOP = 3;

    /**
     * @var int
     */
    public const NOREPLY_WAIT = 4;

    /**
     * @var int
     */
    public const SERVER_INFO = 5;
}
