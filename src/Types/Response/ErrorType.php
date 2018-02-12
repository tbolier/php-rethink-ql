<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Types\Response;

class ErrorType
{
    /**
     * @var int
     */
    public const INTERNAL = 1000000;

    /**
     * @var int
     */
    public const RESOURCE_LIMIT = 2000000;

    /**
     * @var int
     */
    public const QUERY_LOGIC = 3000000;

    /**
     * @var int
     */
    public const NON_EXISTENCE = 3100000;

    /**
     * @var int
     */
    public const OP_FAILED = 4100000;

    /**
     * @var int
     */
    public const OP_INDETERMINATE = 4200000;

    /**
     * @var int
     */
    public const USER = 5000000;

    /**
     * @var int
     */
    public const PERMISSION_ERROR = 6000000;
}
