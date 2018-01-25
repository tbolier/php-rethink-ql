<?php

namespace TBolier\RethinkQL\Response;

class Response implements ResponseInterface
{
    /**
     * @var int
     */
    private $type;

    /**
     * @var array
     */
    private $data;

    /**
     * @var array
     */
    private $backtrace;

    /**
     * @var array
     */
    private $profile;

    /**
     * @var array
     */
    private $note;

    /**
     * @param int $type
     * @param array $data
     * @param array $backtrace
     * @param array|null $profile
     * @param array|null $note
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
