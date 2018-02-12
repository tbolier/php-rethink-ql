<?php

namespace TBolier\RethinkQL\Message;

use TBolier\RethinkQL\Query\Options;
use TBolier\RethinkQL\Query\OptionsInterface;
use TBolier\RethinkQL\Types\Query\QueryType;

class ExprMessage implements MessageInterface
{
    /**
     * @var OptionsInterface
     */
    private $options;

    /**
     * @var string
     */
    private $query;

    /**
     * @var int
     */
    private $queryType;

    /**
     * @param int $queryType
     * @param string $query
     * @param OptionsInterface $options
     */
    public function __construct(int $queryType = null, string $query = '', OptionsInterface $options = null)
    {
        $this->queryType = $queryType ?? QueryType::START;
        $this->query = $query ?? '';
        $this->options = $options ?? new Options();
    }

    /**
     * @inheritdoc
     */
    public function getQueryType(): int
    {
        return $this->queryType;
    }

    /**
     * @inheritdoc
     */
    public function setCommand(int $queryType): MessageInterface
    {
        $this->queryType = $queryType;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setQuery($query): MessageInterface
    {
        $this->query = (string) $query;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOptions(): OptionsInterface
    {
        return $this->options;
    }

    /**
     * @inheritdoc
     */
    public function setOptions(OptionsInterface $options): MessageInterface
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [
            $this->queryType,
            $this->query,
            (object) $this->getOptions(),
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
