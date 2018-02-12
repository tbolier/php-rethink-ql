<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Connection;

interface OptionsInterface
{
    /**
     * @return string
     */
    public function getHostname(): string;

    /**
     * @return int
     */
    public function getPort(): int;

    /**
     * @return string
     */
    public function getDbName(): string;

    /**
     * @return string
     */
    public function getUser(): string;

    /**
     * @return string
     */
    public function getPassword(): string;

    /**
     * @return float
     */
    public function getTimeout(): float;

    /**
     * @return int
     */
    public function getTimeoutStream(): int;

    /**
     * @return bool
     */
    public function isSsl(): bool;

    /**
     * @return bool
     */
    public function hasDefaultDatabase(): bool;
}
