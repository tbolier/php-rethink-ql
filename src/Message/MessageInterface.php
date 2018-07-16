<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Message;

use TBolier\RethinkQL\Query\OptionsInterface;

interface MessageInterface extends \JsonSerializable
{
    public function getQueryType(): int;

    public function setCommand(int $queryType): MessageInterface;

    public function setQuery($query): MessageInterface;

    public function getOptions(): OptionsInterface;

    public function setOptions(OptionsInterface $options): MessageInterface;

    public function toArray(): array;
}
