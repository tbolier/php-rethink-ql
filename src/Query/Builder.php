<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\RethinkInterface;

class Builder implements BuilderInterface
{
    /**
     * @var RethinkInterface
     */
    private $rethink;

    /**
     * @var TableInterface
     */
    private $table;

    /**
     * @var MessageInterface
     */
    private $message;

    /**
     * @param RethinkInterface $rethink
     * @param MessageInterface $message
     */
    public function __construct(RethinkInterface $rethink, MessageInterface $message)
    {
        $this->rethink = $rethink;
        $this->message = $message;
    }

    /**
     * @param string $name
     * @return TableInterface
     */
    public function table(string $name): TableInterface
    {
        if ($this->table) {
            unset($this->table);
        }

        $this->table = new Table($name, $this->rethink, $this->message);

        return $this->table;
    }
}
