<?php

declare(strict_types=1);

use Bogosoft\Configuration\SimpleConfiguration;
use Bogosoft\Configuration\CompositeConfiguration;
use PHPUnit\Framework\TestCase;

class CompositeConfigurationTest extends TestCase
{
    function testCanRetrieveValueFromStoredConfiguration() : void
    {
        $key      = '!';
        $expected = 'Hello, World!';
        $config1  = new SimpleConfiguration([]);
        $config2  = new SimpleConfiguration([$key => $expected]);
        $combined = new CompositeConfiguration($config1, $config2);

        $this->assertEquals($expected, $combined->get($key));
    }

    function testIndicatesHasKeyWhenAStoredConfigurationHasSameKey() : void
    {
        $key      = '!';
        $expected = 'Hello, World!';
        $config1  = new SimpleConfiguration([$key => $expected]);
        $config2  = new SimpleConfiguration([]);
        $combined = new CompositeConfiguration($config1, $config2);

        $this->assertTrue($combined->has($key));
    }

    function testReturnsDefaultValueWhenNoStoredConfigurationHasRequestedKey() : void
    {
        $key      = '!';
        $expected = 'Hello, World!';
        $config1  = new SimpleConfiguration();
        $config2  = new SimpleConfiguration();
        $config3  = new SimpleConfiguration();
        $combined = new CompositeConfiguration($config1, $config2, $config3);

        $this->assertFalse($combined->has($key));
        $this->assertEquals($expected, $combined->get($key, $expected));
    }
}