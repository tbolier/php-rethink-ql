<?php

namespace TBolier\RethinkQL\Query\Logic;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class GetFieldLogic extends AbstractQuery
{
    /**
     * @var string
     */
    private $field;

    /**
     * @param RethinkInterface $rethink
     * @param MessageInterface $message
     * @param string $field
     */
    public function __construct(
        RethinkInterface $rethink,
        MessageInterface $message,
        string $field
    ) {
        parent::__construct($rethink, $message);

        $this->field = $field;
        $this->rethink = $rethink;
        $this->message = $message;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [
            TermType::GET_FIELD,
            [
                [
                    13,
                    [],
                ],
                $this->field
            ],
        ];
    }
}
