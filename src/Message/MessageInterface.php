<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Message;

use TBolier\RethinkQL\Query\OptionsInterface;

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
     * @param array|string $query
     * @return MessageInterface
     */
    public function setQuery($query): MessageInterface;

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
