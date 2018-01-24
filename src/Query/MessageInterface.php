<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

interface MessageInterface extends \JsonSerializable
{
    /**
     * @return int
     */
    public function getQueryType(): int;

    /**
     * @param int $queryType
     * @return MessageInterface
     */
    public function setQueryType(int $queryType): MessageInterface;

    /**
     * @return QueryInterface
     */
    public function getQuery(): QueryInterface;

    /**
     * @param QueryInterface $query
     * @return MessageInterface
     */
    public function setQuery(QueryInterface $query): MessageInterface;

    /**
     * @return array
     */
    public function getOptions(): array;

    /**
     * @param array $options
     * @return MessageInterface
     */
    public function setOptions(array $options): MessageInterface;

    /**
     * @inheritdoc
     */
    public function toArray(): array;
}
