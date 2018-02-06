<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query\Operation;

use TBolier\RethinkQL\Query\AbstractQuery;

abstract class AbstractOperation extends AbstractQuery
{
    /**
     * @inheritdoc
     */
    public function count(): AbstractOperation
    {
        return new Count($this->rethink, $this->message, $this);
    }

    /**
     * @inheritdoc
     */
    public function delete(): AbstractOperation
    {
        return new Delete($this->rethink, $this->message, $this);
    }

    /**
     * @inheritdoc
     */
    public function update(array $document): AbstractOperation
    {
        return new Update($this->rethink, $this->message, $this, $document);
    }

    /**
     * @inheritdoc
     */
    public function insert(array $document): AbstractOperation
    {
        return new Insert($this->rethink, $this->message, $this, $document);
    }
}
