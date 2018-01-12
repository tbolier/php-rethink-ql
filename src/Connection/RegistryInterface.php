<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Connection;

interface RegistryInterface
{
    /**
     * @param string $name
     * @param OptionsInterface $options
     * @return bool
     */
    public function addConnection(string $name, OptionsInterface $options): bool;

    /**
     * @param string $name
     * @return bool
     */
    public function hasConnection(string $name): bool;

    /**
     * @param string $name
     * @return ConnectionInterface
     */
    public function getConnection(string $name): ConnectionInterface;
}