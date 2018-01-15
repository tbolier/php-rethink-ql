<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Query;

use TBolier\RethinkConnect\Document\ManagerInterface;

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
    public function insert(array $documents): bool
    {
        // TODO: Implement insert() method.
    }
}