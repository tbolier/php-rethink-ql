<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Connection;

use TBolier\RethinkQL\Connection\Socket\Socket;
use TBolier\RethinkQL\Connection\Socket\Handshake;
use TBolier\RethinkQL\Types\VersionDummy\Version;

class Registry implements RegistryInterface
{
    /**
     * @var ConnectionInterface[]
     */
    private $connections;

    /**
     * @param array|null $connections
     * @throws ConnectionException
     */
    public function __construct(array $connections = null)
    {
        if ($connections) {
            foreach ($connections as $name => $options) {
                if (!$options instanceof OptionsInterface) {
                    continue;
                }

                $this->addConnection($name, $options);
            }
        }
    }

    /**
     * @inheritdoc
     * @throws ConnectionException
     */
    public function addConnection(string $name, OptionsInterface $options): bool
    {
        if ($this->hasConnection($name)) {
            throw new ConnectionException("The connection {$name} has already been added.", 400);
        }

        $this->connections[$name] = new Connection(
            function () use ($options) {
                return new Socket(
                    $options
                );
            },
            new Handshake($options->getUser(), $options->getPassword(), Version::V1_0),
            $options->getDbname()
        );

        return true;
    }

    /**
     * @inheritdoc
     */
    public function hasConnection(string $name): bool
    {
        return isset($this->connections[$name]);
    }

    /**
     * @inheritdoc
     * @throws ConnectionException
     */
    public function getConnection(string $name): ConnectionInterface
    {
        if (!$this->connections[$name]) {
            throw new ConnectionException("The connection {$name} does not exist.", 400);
        }

        return $this->connections[$name];
    }
}
