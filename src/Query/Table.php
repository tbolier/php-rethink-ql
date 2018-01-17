<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Connection\ConnectionInterface;
use TBolier\RethinkQL\Connection\Exception;
use TBolier\RethinkQL\Document\ManagerInterface;

class Table implements TableInterface
{
    /**
     * @var ManagerInterface
     */
    private $manager;

    /**
     * @param ManagerInterface $manager
     * @param string $name
     */
    public function __construct(ManagerInterface $manager, string $name)
    {
        $this->manager = $manager;
        $this->manager->getConnection()->selectTable($name);
    }

    /**
     * @inheritdoc
     */
    public function count(): int
    {
        // TODO: Implement count() method.
    }

    /**
     * @inheritdoc
     */
    public function get(array $documents): TableInterface
    {
        // TODO: Implement get() method.
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function insert(array $documents): TableInterface
    {
        // TODO: Implement insert() method.
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function update(array $documents): TableInterface
    {
        // TODO: Implement upsert() method.
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function remove(array $documents): TableInterface
    {
        // TODO: Implement remove() method.
        return $this;
    }

    /**
     * @param ConnectionInterface $connection
     * @return array
     * @throws Exception
     */
    public function execute(ConnectionInterface $connection): array
    {
        return $connection->execute();
    }
}
