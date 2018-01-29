<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Response;

interface ResponseInterface
{
    /**
     * @return int
     */
    public function getType(): int;

    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @return array
     */
    public function getBacktrace(): array;

    /**
     * @return array
     */
    public function getProfile(): array;

    /**
     * @return array
     */
    public function getNote(): array;
}
