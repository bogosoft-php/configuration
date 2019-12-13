<?php

declare(strict_types=1);

namespace Bogosoft\Configuration;

class SimpleConfiguration implements IMutableConfiguration
{
    private array $values;

    function __construct(array $values = [])
    {
        $this->values = $values;
    }

    function get(string $key, string $default = '') : string
    {
        return array_key_exists($key, $this->values)
             ? $this->values[$key]
             : $default;
    }

    function getKeys() : iterable
    {
        foreach ($this->values as $key => $value)
        {
            yield $key;
        }
    }

    function has(string $key) : bool
    {
        return array_key_exists($key, $this->values);
    }

    function remove(string $key) : void
    {
        unset($this->values[$key]);
    }

    function set(string $key, string $value) : void
    {
        $this->values[$key] = $value;
    }
}