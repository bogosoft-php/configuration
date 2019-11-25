<?php

namespace Bogosoft\Configuration;

/**
 * Represents a single section of an associated configuration.
 */
interface ConfigurationSectionInterface extends ConfigurationInterface
{
    /**
     * Get the key associated with the current section.
     * 
     * @return string The key of the current section.
     */
    function getKey() : string;

    /**
     * Get the full path of the current section within its assocaited configuration.
     * 
     * @return string The full path of the current section.
     */
    function getPath() : string;
}