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
    public function setCommand(int $queryType): MessageInterface;

    /**
     * @return array
     */
    public function getQuery(): array;

    /**
     * @param array $query
     * @return MessageInterface
     */
    public function setQuery(array $query): MessageInterface;

    /**
     * @return OptionsInterface
     */
    public function getOptions(): OptionsInterface;

    /**
     * @param OptionsInterface $options
     * @return MessageInterface
     */
    public function setOptions(OptionsInterface $options): MessageInterface;

    /**
     * @inheritdoc
     */
    public function toArray(): array;
}
