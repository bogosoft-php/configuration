<?php

namespace Bogosoft\Configuration;

/**
 * Represents any read-only configuration type.
 */
interface IConfiguration
{
    /**
     * Get a value from the current configuration.
     * 
     * @param string $key     The key of a value to return
     * @param string $default A default value to return if the given key
     *                        does not exist.
     * 
     * @return string The referenced value or the default value if the given
     *                key does not exist within the current configuration.
     */
    function get(string $key, string $default = '') : string;

    /**
     * Get all of the keys from the current configuration.
     * 
     * @return iterable A sequence of all of the keys in the current configuration.
     */
    function getKeys() : iterable;

    /**
     * Determine whether or not the current configuration contains the given key.
     * 
     * @param string $key A key by which to query the current configuration.
     * 
     * @return bool A value indicating whether or not the given key exists
     *              within the current configuration.
     */
    function has(string $key) : bool;
}