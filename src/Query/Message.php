<?php
namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Types\Query\QueryType;

class Message implements MessageInterface
{
    /**
     * @var int
     */
    private $queryType;

    /**
     * @var QueryInterface
     */
    private $query;

    /**
     * @var array
     */
    private $options;

    /**
     * @param int $queryType
     * @param QueryInterface $query
     * @param array $options
     */
    public function __construct(int $queryType = null, QueryInterface $query = null, array $options = null)
    {
        $this->queryType = $queryType ?? QueryType::START;
        $this->query = $query ?? new Query([]);
        $this->options = $options ?? (object)[];
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
    public function setQueryType(int $queryType): MessageInterface
    {
        $this->queryType = $queryType;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getQuery(): QueryInterface
    {
        return $this->query;
    }

    /**
     * @inheritdoc
     */
    public function setQuery(QueryInterface $query): MessageInterface
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @inheritdoc
     */
    public function setOptions(array $options): MessageInterface
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
            $this->getQuery(),
            $this->getOptions()
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
