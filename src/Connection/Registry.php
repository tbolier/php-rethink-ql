<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Connection;

class Registry implements RegistryInterface
{
    /**
     * @var ConnectionInterface[]
     */
    private $connections;

    /**
     * @param array|null $connections
     * @throws Exception
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
     * @throws Exception
     */
    public function addConnection(string $name, OptionsInterface $options): bool
    {
        if($this->hasConnection($name)) {
            throw new Exception("The connection {$name} has already been added.", 400);
        }

        $this->connections[$name] = new Connection($options);

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
     * @throws Exception
     */
    public function getConnection(string $name): ConnectionInterface
    {
        if ($this->connections[$name]) {
            throw new Exception("The connection {$name} does not exist.", 400);
        }

        return $this->connections[$name];
    }
}
