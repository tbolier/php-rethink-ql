<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Transformation;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class Skip extends AbstractTransformation
{
    /**
     * @var int
     */
    private $n;

    /**
     * @var QueryInterface
     */
    private $query;

    /**
     * @param RethinkInterface $rethink
     * @param MessageInterface $message
     * @param QueryInterface $query
     * @param int $n
     */
    public function __construct(
        RethinkInterface $rethink,
        MessageInterface $message,
        QueryInterface $query,
        int $n
    ) {
        parent::__construct($rethink, $message);

        $this->query = $query;
        $this->n = $n;
        $this->rethink = $rethink;
        $this->message = $message;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [
            TermType::SKIP,
            [
                $this->query->toArray(),
                [
                    TermType::DATUM,
                    $this->n,
                ],
            ],
        ];
    }
}
