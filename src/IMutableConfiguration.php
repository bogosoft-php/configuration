<?php

namespace Bogosoft\Configuration;

/**
 * Represents any read-write configuration type.
 */
interface IMutableConfiguration extends IConfiguration
{
    /**
     * Remove a key-value pair from the current configuration.
     * 
     * @param string $key The value to remove by its assigned key.
     */
    function remove(string $key) : void;

    /**
     * Set a value on the current configuration.
     * 
     * Implementations SHOULD override any value already assigned
     * to the given key.
     * 
     * @param string $key   The key of the new value.
     * @param string $value The new value.
     */
    function set(string $key, string $value) : void;
}