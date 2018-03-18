<?php

namespace TBolier\RethinkQL\Query\Logic;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class FuncLogic extends AbstractQuery
{
    /**
     * @var QueryInterface
     */
    private $functions;

    /**
     * @param RethinkInterface $rethink
     * @param MessageInterface $message
     * @param QueryInterface $functions
     */
    public function __construct(
        RethinkInterface $rethink,
        MessageInterface $message,
        QueryInterface $functions
    ) {
        parent::__construct($rethink, $message);

        $this->functions = $functions;
        $this->rethink = $rethink;
        $this->message = $message;
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
                    $this->functions->toArray(),
                ],
            ];
    }
}
