<?php

namespace TBolier\RethinkQL\Query\Logic;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Query\Operation\AbstractOperation;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class FuncLogic extends AbstractOperation
{
    /**
     * @var QueryInterface
     */
    private $query;

    /**
     * @param RethinkInterface $rethink
     * @param MessageInterface $message
     * @param QueryInterface $query
     */
    public function __construct(
        RethinkInterface $rethink,
        MessageInterface $message,
        QueryInterface $query
    ) {
        parent::__construct($rethink, $message);

        $this->rethink = $rethink;
        $this->message = $message;
        $this->query = $query;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return
            [
                TermType::FUNC,
                [
                    [
                        TermType::MAKE_ARRAY,
                        [
                            TermType::DATUM,
                        ],
                    ],
                    $this->query->toArray(),
                ],
            ];
    }
}
