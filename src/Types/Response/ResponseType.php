<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Types\Response;

class ResponseType
{
    /**
     * @var int
     */
    public const SUCCESS_ATOM = 1;

    /**
     * @var int
     */
    public const SUCCESS_SEQUENCE = 2;

    /**
     * @var int
     */
    public const SUCCESS_PARTIAL = 3;

    /**
     * @var int
     */
    public const WAIT_COMPLETE = 4;

    /**
     * @var int
     */
    public const SERVER_INFO = 5;

    /**
     * @var int
     */
    public const CLIENT_ERROR = 16;

    /**
     * @var int
     */
    public const COMPILE_ERROR = 17;

    /**
     * @var int
     */
    public const RUNTIME_ERROR = 18;
}
