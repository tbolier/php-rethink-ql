<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query\Transformation;

use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\Query\Operation\OperationTrait;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class OrderBy extends AbstractQuery
{
    use TransformationTrait;
    use OperationTrait;

    /**
     * @var mixed|QueryInterface
     */
    private $key;

    /**
     * @var QueryInterface
     */
    private $query;

    public function __construct(
        RethinkInterface $rethink,
        QueryInterface $query,
        $key
    ) {
        parent::__construct($rethink);

        $this->query = $query;
        $this->key = $key;
        $this->rethink = $rethink;
    }

    public function toArray(): array
    {
        $ordering = $this->key instanceof QueryInterface ? $this->key->toArray() : $this->key;

        return [
            TermType::ORDER_BY,
            [
                $this->query->toArray(),
                $ordering,
            ],
        ];
    }
}
