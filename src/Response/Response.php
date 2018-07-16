<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Response;

use TBolier\RethinkQL\Types\Frame\FrameType;
use TBolier\RethinkQL\Types\Query\QueryType;

class Response implements ResponseInterface
{
    /**
     * @var array|null
     */
    private $backtrace;
    /**
     * @var array|null
     */
    private $data;
    /**
     * @var array|null
     */
    private $note;
    /**
     * @var array|null
     */
    private $profile;
    /**
     * @var int
     */
    private $type;

    public function __construct(int $t = null, array $r = null, array $b = null, array $p = null, array $n = null)
    {
        !$t ?: $this->type = $t;
        !$r ?: $this->data = $r;
        !$b ?: $this->backtrace = $b;
        !$p ?: $this->profile = $p;
        !$n ?: $this->note = $n;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function getData()
    {
        if (!\is_array($this->data)) {
            return null;
        }

        if (isset($this->data[0]['$reql_type$']) && $this->data[0]['$reql_type$'] === 'GROUPED_DATA') {
            $groups = [];
            foreach ($this->data[0]['data'] as $group) {
                $groups[] = [
                    'group' => $group[0],
                    'reduction' => $group[1],
                ];
            }

            return $groups;
        }

        return \count($this->data) === 1 && array_key_exists(0, $this->data) ? $this->data[0] : $this->data;
    }

    public function isAtomic(): bool
    {
        return \is_string($this->data) || (!\is_null($this->data) && \count($this->data) === 1);
    }

    public function getBacktrace(): ?array
    {
        return $this->backtrace;
    }

    public function getProfile(): ?array
    {
        return $this->profile;
    }

    public function getNote(): ?array
    {
        return $this->note;
    }
}
