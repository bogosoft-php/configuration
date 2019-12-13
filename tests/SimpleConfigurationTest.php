<?php

declare(strict_types=1);

use Bogosoft\Configuration\SimpleConfiguration;
use PHPUnit\Framework\TestCase;

class SimpleConfigurationTest extends TestCase
{
    function testCanInstantiate() : void
    {
        $config = new SimpleConfiguration();

        $this->assertInstanceOf(SimpleConfiguration::class, $config);
    }

    function testCanRemoveKeyValuePair() : void
    {
        $key    = '#';
        $config = new SimpleConfiguration([$key => '...']);

        $this->assertTrue($config->has($key));

        $config->remove($key);

        $this->assertFalse($config->has($key));
    }

    function testCanRetrieveAllKeys() : void
    {
        $expected = [];

        for ($i = 0; $i < 8; $i++)
        {
            $expected["$i"] = '';
        }

        $config = new SimpleConfiguration($expected);
        $actual = iterator_to_array($config->getKeys());

        $this->assertEquals(count($expected), count($actual));

        $i = 0;

        foreach ($expected as $key => $value)
        {
            $this->assertEquals($key, $actual[$i++]);
        }
    }

    function testCanRetrieveValue() : void
    {
        $key      = 'salutation';
        $expected = 'Hello, World!';
        $config   = new SimpleConfiguration([$key => $expected]);
        $actual   = $config->get($key);

        $this->assertEquals($expected, $actual);
    }

    function testCanSetValue() : void
    {
        $after  = 'future';
        $before = 'past';
        $key    = 'tense';
        $config = new SimpleConfiguration([$key => $before]);

        $this->assertFalse($config->get($key) === $after);

        $config->set($key, $after);

        $this->assertEquals($after, $config->get($key));
    }

    function testReportsHavingValueWhenKeyExists() : void
    {
        $key    = 'key';
        $config = new SimpleConfiguration([$key => '...']);

        $this->assertTrue($config->has($key));
    }

    function testReturnsDefaultValueWhenTryingToGetValueByNonExistentKey() : void
    {
        $key      = '@';
        $expected = 'I am a default value.';
        $config   = new SimpleConfiguration([]);

        $this->assertFalse($config->has($key));
        $this->assertEquals($expected, $config->get($key, $expected));
    }
}