<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Connection;

interface OptionsInterface
{
    public function getHostname(): string;

    public function getPort(): int;

    public function getDbName(): string;

    public function getUser(): string;

    public function getPassword(): string;

    public function getTimeout(): float;

    public function getTimeoutStream(): int;

    public function isSsl(): bool;

    public function hasDefaultDatabase(): bool;
}
