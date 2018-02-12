<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Types\Response;

class ResponseNote
{
    /**
     * @var int
     */
    public const SEQUENCE_FEED = 1;

    /**
     * @var int
     */
    public const ATOM_FEED = 2;

    /**
     * @var int
     */
    public const ORDER_BY_LIMIT_FEED = 3;

    /**
     * @var int
     */
    public const UNIONED_FEED = 4;

    /**
     * @var int
     */
    public const INCLUDES_STATES = 5;
}
