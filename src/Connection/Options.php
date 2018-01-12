<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Connection;

class Options implements OptionsInterface
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @var int
     */
    private $timeout;

    /**
     * @var bool
     */
    private $ssl;

    /**
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->host = $options['host'] ?? 'localhost';
        $this->port = $options['port'] ?? 28015;
        $this->user = $options['user'] ?? '';
        $this->password = $options['password'] ?? '';
        $this->timeout = $options['timeout'] ?? 5;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
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
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @return bool
     */
    public function isSsl(): bool
    {
        return $this->ssl;
    }
}
