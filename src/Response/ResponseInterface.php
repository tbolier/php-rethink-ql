<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Response;

interface ResponseInterface
{
    /**
     * @return int
     */
    public function getType(): ?int;

    /**
     * @return string|array
     */
    public function getData();

    /**
     * @return bool
     */
    public function isAtomic(): bool;

    /**
     * @return array
     */
    public function getBacktrace(): ?array;

    /**
     * @return array
     */
    public function getProfile(): ?array;

    /**
     * @return array
     */
    public function getNote(): ?array;
}
