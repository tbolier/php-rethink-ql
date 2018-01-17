<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Document\ManagerInterface;

class Builder implements BuilderInterface
{
    /**
     * @var ManagerInterface
     */
    private $manager;

    /**
     * @var TableInterface
     */
    private $table;

    /**
     * @param ManagerInterface $manager
     */
    public function __construct(ManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param string $name
     *
     * @return TableInterface
     */
    public function table(string $name): TableInterface
    {
        if ($this->table) {
            unset($this->table);
        }

        $this->table = new Table($this->manager, $name);

        return $this->table;
    }
}