<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Response;

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

    /**
     * @param int|null $t
     * @param array|null $r
     * @param array|null $b
     * @param array|null $p
     * @param array|null $n
     */
    public function __construct(int $t = null, array $r = null, array $b = null, array $p = null, array $n = null)
    {
        !$t ?: $this->type = $t;
        !$r ?: $this->data = $r;
        !$b ?: $this->backtrace = $b;
        !$p ?: $this->profile = $p;
        !$n ?: $this->note = $n;
    }

    /**
     * @inheritdoc
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        if (!\is_array($this->data)) {
            return null;
        }

        return \count($this->data) === 1 && array_key_exists(0, $this->data) ? $this->data[0] : $this->data;
    }

    /**
     * @return bool
     */
    public function isAtomic(): bool
    {
        return \is_string($this->data) || \count($this->data) === 1;
    }

    /**
     * @inheritdoc
     */
    public function getBacktrace(): ?array
    {
        return $this->backtrace;
    }

    /**
     * @inheritdoc
     */
    public function getProfile(): ?array
    {
        return $this->profile;
    }

    /**
     * @inheritdoc
     */
    public function getNote(): ?array
    {
        return $this->note;
    }
}
