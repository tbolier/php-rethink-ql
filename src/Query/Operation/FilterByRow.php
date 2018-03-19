<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query\Operation;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\Query\Transformation\AbstractTransformationCompound;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class FilterByRow extends AbstractTransformationCompound
{
    /**
     * @var QueryInterface
     */
    private $functionQuery;

    /**
     * @var QueryInterface
     */
    private $query;

    /**
     * @param RethinkInterface $rethink
     * @param MessageInterface $message
     * @param QueryInterface $query
     * @param QueryInterface $manipulation
     */
    public function __construct(
        RethinkInterface $rethink,
        MessageInterface $message,
        QueryInterface $query,
        QueryInterface $manipulation
    ) {
        parent::__construct($rethink, $message);

        $this->query = $query;
        $this->functionQuery = $manipulation;
        $this->rethink = $rethink;
        $this->message = $message;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [
            TermType::FILTER,
            [
                $this->query->toArray(),
                $this->functionQuery->toArray(),
            ],
        ];
    }
}
