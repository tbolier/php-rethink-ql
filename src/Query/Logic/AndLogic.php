<?php

namespace TBolier\RethinkQL\Query\Logic;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Query\Operation\AbstractOperation;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class AndLogic extends AbstractOperation
{
    /**
     * @var QueryInterface
     */
    private $functionOne;

    /**
     * @var QueryInterface
     */
    private $functionTwo;

    /**
     * @param RethinkInterface $rethink
     * @param MessageInterface $message
     * @param QueryInterface $functionOne
     * @param QueryInterface $functionTwo
     */
    public function __construct(
        RethinkInterface $rethink,
        MessageInterface $message,
        QueryInterface $functionOne,
        QueryInterface $functionTwo
    ) {
        parent::__construct($rethink, $message);

        $this->rethink = $rethink;
        $this->message = $message;
        $this->functionOne = $functionOne;
        $this->functionTwo = $functionTwo;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return
            [
                TermType::AND,
                [
                    $this->functionOne->toArray(),
                    $this->functionTwo->toArray(),
                ],
            ];
    }
}
