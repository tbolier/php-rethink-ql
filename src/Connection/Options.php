<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Connection;

class Options implements OptionsInterface
{
    /**
     * @var string
     */
    private $hostname;

    /**
     * @var int
     */
    private $port;

    /**
     * @var string
     */
    private $dbname;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @var float
     */
    private $timeout;

    /**
     * @var int
     */
    private $timeoutStream;

    /**
     * @var bool
     */
    private $ssl;

    public function __construct(array $options = [])
    {
        $this->hostname = $options['hostname'] ?? 'localhost';
        $this->port = $options['port'] ?? 28015;
        $this->dbname = $options['dbname'] ?? '';
        $this->user = $options['user'] ?? '';
        $this->password = $options['password'] ?? '';
        $this->timeout = $options['timeout'] ?? 1.0;
        $this->timeoutStream = $options['timeout_stream'] ?? 3;
        $this->ssl = $options['ssl'] ?? false;
    }

    public function getHostname(): string
    {
        return $this->hostname;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getDbName(): string
    {
        return $this->dbname;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getTimeout(): float
    {
        return $this->timeout;
    }

    public function getTimeoutStream(): int
    {
        return $this->timeoutStream;
    }

    public function isSsl(): bool
    {
        return $this->ssl;
    }

    public function hasDefaultDatabase(): bool
    {
        return !empty($this->dbname);
    }
}
