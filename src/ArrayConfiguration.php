<?php

declare(strict_types=1);

namespace Bogosoft\Configuration;

use \ArrayAccess;
use \Bogosoft\Core\NotSupportedException;
use \InvalidArgumentException;
use \OutOfBoundsException;
use \UnexpectedValueException;

/**
 * An in-memory, array-based implementation of the ConfigurationInterface contract.
 */
class ArrayConfiguration implements ConfigurationSectionInterface
{
    /**
     * Create a new configuration from a given array.
     * 
     * @param array $values An array whose values will become the new configuration.
     * 
     * @throws InvalidArgumentException when a key in the given array is not a string or when a value is not
     *                                  an array or a string.
     * 
     * @return ConfigurationInterface A new configuration.
     */
    public static function from(array $values) : ConfigurationInterface
    {
        return self::fromInternal($values, '', '');
    }

    private static function fromInternal(array $values, string $key, string $path) : ConfigurationInterface
    {
        $data = [];
        $path = '' === $path ? $key : "$path:$key";

        foreach ($values as $k => $v)
        {
            if (!is_string($k))
            {
                throw new InvalidArgumentException('Given key is not a string.');
            }
            elseif (is_array($v))
            {
                $data[$k] = self::fromInternal($v, $k, $path);
            }
            elseif (is_string($v))
            {
                $data[$k] = $v;
            }
            else
            {
                throw new InvalidArgumentException("Value for key, '$k', must be a string.");
            }
        }

        return new ArrayConfiguration($data, $key, $path);
    }

    private $key;
    private $path;
    private $values;

    private function __construct(array $values, string $key, string $path)
    {
        $this->key    = $key ?? '';
        $this->path   = $path ?? '';
        $this->values = $values;
    }

    public function getChildren() : iterable
    {
        foreach ($this->values as $key => $value)
        {
            if ($value instanceof ConfigurationSectionInterface)
            {
                yield $value;
            }
        }
    }

    public function getKey() : string
    {
        return $this->key;
    }

    public function getPath() : string
    {
        return $this->path;
    }

    public function getSection(string $key) : ConfigurationSectionInterface
    {
        return $this->getSectionInternal($key, $this->path);
    }

    private function getSectionInternal(string $key, string $path) : ConfigurationSectionInterface
    {
        $offset = $key;

        $index = strpos($offset, ':') ?: -1;

        if (-1 === $index)
        {
            $key = $offset;
        }
        else
        {
            $key = substr($offset, 0, $index);

            $offset = substr($offset, $index + 1);
        }

        if (!array_key_exists($key, $this->values))
        {
            throw new OutOfBoundException("Section, '$path', does not exist.");
        }

        $value = $this->values[$key];

        $path = $path === '' ? $key : "$path:$key";

        if ($value instanceof ConfigurationSectionInterface)
        {
            return -1 === $index ? $value : $value->getSection($offset, $path);
        }
        else
        {
            throw new UnexpectedValueException("Expected configuration section from '$path'; got string.");
        }
    }

    public function offsetExists($offset)
    {
        if (false === ($index = strpos($offset, ':')))
        {
            return array_key_exists($offset, $values);
        }
        else
        {
            $key = substr($offset, 0, $index);

            if (!array_key_exists($key, $values))
            {
                return false;
            }

            $value = $values[$key];

            return $value instanceof ConfigurationSectionInterface
                && $value->offsetExists(substr($offset, $index + 1));
        }
    }

    public function offsetGet($offset)
    {
        return $this->offsetGetInternal($offset, $this->path);
    }

    private function offsetGetInternal($offset, string $path)
    {
        $index = strpos($offset, ':') ?: -1;

        if (-1 === $index)
        {
            $key = $offset;
        }
        else
        {
            $key = substr($offset, 0, $index);

            $offset = substr($offset, $index + 1);
        }

        if (!array_key_exists($key, $this->values))
        {
            throw new OutOfBoundsException("'$path' is not a valid key for this configuration.");
        }

        $value = $this->values[$key];

        $path = $path === '' ? $key : "$path:$key";

        if (-1 === $index)
        {
            if ($value instanceof ConfigurationSectionInterface)
            {
                throw new UnexpectedValueException("Expected string from '$path'; got configuration section.");
            }

            return $value;
        }
        else
        {
            if ($value instanceof ConfigurationSectionInterface)
            {
                return $value->offsetGetInternal($offset, $path);
            }
            else
            {
                throw new UnexpectedValueException("Expected configuration section from '$path'; got string.");
            }
        }
    }

    public function offsetSet($offset, $value)
    {
        throw new RuntimeException();
    }

    public function offsetUnset($offset)
    {
        throw new NotSupportedException();
    }
}