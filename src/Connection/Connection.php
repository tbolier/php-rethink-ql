<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Connection;

class Connection implements ConnectionInterface
{
    /**
     * @var OptionsInterface
     */
    private $options;

    /**
     * @var bool|resource
     */
    public $socket;

    /**
     * @param OptionsInterface $options
     */
    public function __construct(OptionsInterface $options)
    {
        $this->options = $options;
    }

    /**
     * @inheritdoc
     */
    public function connect(): void
    {
        if ($this->isConnected()) {
            return;
        }

        if ($this->options->isSsl()) {
            $this->socket = stream_socket_client(
                'ssl://' . $this->options->getHost() . ':' . $this->options->getPort(),
                $errno,
                $errstr,
                ini_get('default_socket_timeout'),
                STREAM_CLIENT_CONNECT
            );
        } else {
            $this->socket = stream_socket_client('tcp://' . $this->options->getHost() . ':' . $this->options->getPort(), $errno, $errstr);
        }

        if ($this->options->getDefaultDatabase()) {
            $this->selectDatabase($this->options->getDefaultDatabase());
        }
    }

    public function execute()
    {

    }

    /**
     * @inheritdoc
     */
    public function selectDatabase(string $name): void
    {
        // TODO: Implement selectDatabase() method.
    }

    /**
     * @inheritdoc
     */
    public function selectTable($name): void
    {
        // TODO: Implement selectTable() method.
    }

    /**
     * @return bool
     */
    public function isConnected(): bool
    {
        return $this->socket ? true : false;
    }
}
