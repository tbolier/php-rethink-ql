<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Query\Operation\Get;
use TBolier\RethinkQL\Query\Operation\IndexCreate;
use TBolier\RethinkQL\Query\Operation\IndexDrop;
use TBolier\RethinkQL\Query\Operation\IndexList;
use TBolier\RethinkQL\Query\Operation\IndexRename;
use TBolier\RethinkQL\Query\Transformation\AbstractTransformationCompound;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class Table extends AbstractTransformationCompound implements TableInterface
{
    /**
     * @var array
     */
    private $query;

    /**
     * @param string $name
     * @param RethinkInterface $rethink
     */
    public function __construct(string $name, RethinkInterface $rethink)
    {
        parent::__construct($rethink);

        $this->rethink = $rethink;
        

        $this->query = [
            TermType::TABLE,
            [
                $name,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function get($key): AbstractQuery
    {
        return new Get($this->rethink, $this, $key);
    }

    /**
     * @inheritdoc
     */
    public function indexCreate(string $name): AbstractQuery
    {
        return new IndexCreate($this->rethink, $this, $name);
    }

    /**
     * @inheritdoc
     */
    public function indexDrop(string $name): AbstractQuery
    {
        return new IndexDrop($this->rethink, $this, $name);
    }

    /**
     * @inheritdoc
     */
    public function indexList(): AbstractQuery
    {
        return new IndexList($this->rethink, $this);
    }

    /**
     * @inheritdoc
     */
    public function indexRename(string $oldValue, string $newValue): AbstractQuery
    {
        return new IndexRename($this->rethink, $this, $oldValue, $newValue);
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return $this->query;
    }
}
