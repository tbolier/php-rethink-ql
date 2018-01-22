<?php
declare(strict_types=1);

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

    /**
     * @param array $options
     */
    public function __construct($options = [])
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

    /**
     * @return string
     */
    public function getHostname(): string
    {
        return $this->hostname;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getDbname(): string
    {
        return $this->dbname;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return float
     */
    public function getTimeout(): float
    {
        return $this->timeout;
    }

    /**
     * @return int
     */
    public function getTimeoutStream(): int
    {
        return $this->timeoutStream;
    }

    /**
     * @return bool
     */
    public function isSsl(): bool
    {
        return $this->ssl;
    }

    /**
     * @return bool
     */
    public function hasDefaultDatabase(): bool
    {
        return !empty($this->dbname);
    }
}
