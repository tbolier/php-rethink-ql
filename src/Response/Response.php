<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Response;

class Response implements ResponseInterface
{
    /**
     * @var int
     */
    private $type;

    /**
     * @var array|null
     */
    private $data;

    /**
     * @var array|null
     */
    private $backtrace;

    /**
     * @var array|null
     */
    private $profile;

    /**
     * @var array|null
     */
    private $note;

    /**
     * @param int|null $t
     * @param array|null $r
     * @param array|null $b
     * @param array|null $p
     * @param array|null $n
     */
    public function __construct(int $t = null, array $r = null, array $b = null, array $p = null, array $n = null)
    {
        $this->type = $t;
        $this->data = $r;
        $this->backtrace = $b;
        $this->profile = $p;
        $this->note = $n;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getBacktrace(): array
    {
        return $this->backtrace;
    }

    /**
     * @return array
     */
    public function getProfile(): array
    {
        return $this->profile;
    }

    /**
     * @return array
     */
    public function getNote(): array
    {
        return $this->note;
    }
}
