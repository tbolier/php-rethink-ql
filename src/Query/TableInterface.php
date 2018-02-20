<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Query\Operation\OperationInterface;

interface TableInterface extends OperationInterface
{
    /**
     * @param string|int $key
     * @return AbstractQuery
     */
    public function get($key): AbstractQuery;

    /**
     * @param string $name
     * @return AbstractQuery
     */
    public function indexCreate(string $name): AbstractQuery;

    /**
     * @param string $name
     * @return AbstractQuery
     */
    public function indexDrop(string $name): AbstractQuery;

    /**
     * @return AbstractQuery
     */
    public function indexList(): AbstractQuery;

    /**
     * @param string $oldValue
     * @param string $newValue
     * @return AbstractQuery
     */
    public function indexRename(string $oldValue, string $newValue): AbstractQuery;
}
