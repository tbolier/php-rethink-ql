<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Query\Aggregation\AggregationTrait;
use TBolier\RethinkQL\Query\Operation\Between;
use TBolier\RethinkQL\Query\Manipulation\HasFields;
use TBolier\RethinkQL\Query\Manipulation\ManipulationTrait;
use TBolier\RethinkQL\Query\Operation\Changes;
use TBolier\RethinkQL\Query\Operation\Get;
use TBolier\RethinkQL\Query\Operation\IndexCreate;
use TBolier\RethinkQL\Query\Operation\IndexDrop;
use TBolier\RethinkQL\Query\Operation\IndexList;
use TBolier\RethinkQL\Query\Operation\IndexRename;
use TBolier\RethinkQL\Query\Operation\OperationTrait;
use TBolier\RethinkQL\Query\Operation\Sync;
use TBolier\RethinkQL\Query\Transformation\TransformationTrait;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class Table extends AbstractQuery
{
    use AggregationTrait;
    use OperationTrait;
    use TransformationTrait;

    /**
     * @var array
     */
    private $query;

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

    public function changes(array $options = null): Changes
    {
        return new Changes($this->rethink, $this, $options);
    }

    public function get($key): Get
    {
        return new Get($this->rethink, $this, $key);
    }

    public function indexCreate(string $name): AbstractQuery
    {
        return new IndexCreate($this->rethink, $this, $name);
    }

    public function indexDrop(string $name): AbstractQuery
    {
        return new IndexDrop($this->rethink, $this, $name);
    }

    public function indexList(): AbstractQuery
    {
        return new IndexList($this->rethink, $this);
    }

    public function indexRename(string $oldValue, string $newValue): AbstractQuery
    {
        return new IndexRename($this->rethink, $this, $oldValue, $newValue);
    }

    public function between($min, $max, array $options = null): Between
    {
        return new Between($this->rethink, $this, $min, $max, $options);
    }
  
    public function hasFields(...$keys)
    {
        return new HasFields($this->rethink, $this, $keys);
    }

    public function sync()
    {
        return new Sync($this->rethink, $this);
    }

    public function toArray(): array
    {
        return $this->query;
    }
}
