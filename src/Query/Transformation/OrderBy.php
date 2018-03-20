<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Transformation;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class OrderBy extends AbstractTransformationCompound
{
    /**
     * @var mixed|QueryInterface
     */
    private $key;

    /**
     * @var QueryInterface
     */
    private $query;

    /**
     * @param RethinkInterface $rethink
     * @param QueryInterface $query
     * @param mixed $key
     */
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

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        $ordering = $this->key instanceof QueryInterface ? $this->key->toArray() : $this->key;

        return [
            TermType::ORDER_BY,
            [
                $this->query->toArray(),
                $ordering
            ],
        ];
    }
}
