<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Connection;

interface OptionsInterface
{
    /**
     * @return string
     */
    public function getHost(): string;

    /**
     * @return int
     */
    public function getPort(): int;

    /**
     * @return string
     */
    public function getDefaultDatabase(): string;

    /**
     * @return string
     */
    public function getUser(): string;

    /**
     * @return string
     */
    public function getPassword(): string;

    /**
     * @return int
     */
    public function getTimeout(): int;

    /**
     * @return bool
     */
    public function isSsl(): bool;
}
