<?php

namespace Bogosoft\Configuration;

use \ArrayAccess;

/**
 * Represents a queryable configuration.
 */
interface ConfigurationInterface extends ArrayAccess
{
    /**
     * Get the immediate descendants configuration sub-sections from the current configuration.
     * 
     * @return iterable A sequence of objects implementing ConfigurationSectionInterface.
     */
    function getChildren() : iterable;

    /**
     * Get a single section of the current configuration.
     * 
     * @param string $key The key of the section to get.
     * 
     * @return ConfigurationSectionInterface A subset of the current configuration as a configuration section.
     */
    function getSection(string $key) : ConfigurationSectionInterface;
}