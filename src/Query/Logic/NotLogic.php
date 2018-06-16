<?php
declare(strict_types=1);
namespace TBolier\RethinkQL\Query\Logic;

use TBolier\RethinkQL\Query\Operation\AbstractOperation;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class NotLogic extends AbstractOperation
{
    /**
     * @var QueryInterface
     */
    private $query;

    /**
     * @param RethinkInterface $rethink
     * @param QueryInterface $query
     * @param mixed $value
     */
    public function __construct(
        RethinkInterface $rethink,
        QueryInterface $query
    ) {
        parent::__construct($rethink);

        $this->rethink = $rethink;
        $this->query = $query;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return
            [
                TermType::NOT,
                [
                    $this->query->toArray(),
                ],
            ];
    }
}
