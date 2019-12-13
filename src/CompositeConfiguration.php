<?php

declare(strict_types=1);

namespace Bogosoft\Configuration;

/**
 * A composite implementation of the configuration contract.
 * 
 * This class allows multiple configurations to behave as if they
 * are a single configuration.
 * 
 * This class CANNOT be inherited.
 */
final class CompositeConfiguration implements IConfiguration
{
    private $configurations;

    /**
     * Create a new composite configuration.
     * 
     * @param IConfiguration ...$configuration Zero or more configurations from which
     *                                         to form create the composite.
     */
    function __construct(IConfiguration ... $configurations)
    {
        $this->configurations = $configurations;
    }

    function get(string $key, string $default = '') : string
    {
        foreach ($this->configurations as $config)
        {
            if ($config->has($key))
            {
                return $config->get($key);
            }
        }

        return $default;
    }

    function getKeys() : iterable
    {
        $found = [];

        foreach ($this->configurations as $config)
        {
            foreach ($config->getKeys() as $key)
            {
                if (!array_key_exists($key, $found))
                {
                    $found[] = $key;

                    yield $key;
                }
            }
        }
    }

    function has(string $key) : bool
    {
        foreach ($this->configurations as $config)
        {
            if ($config->has($key))
            {
                return true;
            }
        }

        return false;
    }
}