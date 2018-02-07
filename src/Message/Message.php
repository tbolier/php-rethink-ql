<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Message;

use TBolier\RethinkQL\Query\Options;
use TBolier\RethinkQL\Query\OptionsInterface;
use TBolier\RethinkQL\Types\Query\QueryType;

class Message implements MessageInterface
{
    /**
     * @var int
     */
    private $queryType;

    /**
     * @var array
     */
    private $query;

    /**
     * @var OptionsInterface
     */
    private $options;

    /**
     * @param int $queryType
     * @param array $query
     * @param OptionsInterface $options
     */
    public function __construct(int $queryType = null, array $query = null, OptionsInterface $options = null)
    {
        $this->queryType = $queryType ?? QueryType::START;
        $this->query = $query ?? [];
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
        $this->query = $query;

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
            (object)$this->getOptions()
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
