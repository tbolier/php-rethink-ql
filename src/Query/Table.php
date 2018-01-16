<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Query;

use TBolier\RethinkConnect\Document\ManagerInterface;

class Table implements TableInterface
{
    public $string;

    /**
     * @var ManagerInterface
     */
    private $manager;

    /**
     * @param ManagerInterface $manager
     * @param string           $name
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
    }

    /**
     * @inheritdoc
     */
    public function insert(array $documents): bool
    {
        // TODO: Implement insert() method.
    }

    /**
     * @inheritdoc
     */
    public function update(array $documents): bool
    {
        // TODO: Implement upsert() method.
    }

    /**
     * @inheritdoc
     */
    public function remove(array $documents): bool
    {
        // TODO: Implement remove() method.
    }
}