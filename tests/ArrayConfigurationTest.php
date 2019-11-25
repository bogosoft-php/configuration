<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Bogosoft\Configuration\ArrayConfiguration;
use Bogosoft\Configuration\ChangeTokenInterface;
use Bogosoft\Configuration\ConfigurationSectionInterface;
use Bogosoft\Core\NotSupportedException;
use PHPUnit\Framework\TestCase;

final class ArrayConfigurationTest extends TestCase
{
    public function testCanCreateFromSimpleArrayOfStringKeysToStringValues() : void
    {
        $key = 'TestKey1';
        $val = 'Hello, World!';

        $values = [$key => $val];

        $config = ArrayConfiguration::from($values);

        $this->assertEquals($val, $config[$key]);
    }

    public function testCanRetrieveDeepValueWhenCreatedFromNestedArray() : void
    {
        $expected = 'db.example.com';

        $values = [
            'database' => [
                'host' => $expected
            ]
        ];

        $config = ArrayConfiguration::from($values);

        $this->assertEquals($expected, $config['database:host']);
    }

    public function testCanRetrieveKeyFromReturnedSection() : void
    {
        $expected = 'database';

        $values = [
            $expected => [
                'port' => '3306'
            ]
        ];

        $config = ArrayConfiguration::from($values);

        $db = iterator_to_array($config->getChildren())[0];

        $this->assertTrue($db instanceof ConfigurationSectionInterface);

        $this->assertEquals($expected, $db->getKey());
    }

    public function testCanRetrievePathFromReturnedSection() : void
    {
        $database = 'database';
        $mysql    = 'mysql';
        $expected = "$database:$mysql";

        $values = [
            $database => [
                $mysql => [
                    'user' => 'root'
                ]
            ]
        ];

        $config = ArrayConfiguration::from($values);

        $section = $config->getSection($expected);

        $this->assertTrue($section instanceof ConfigurationSectionInterface);

        $this->assertEquals($expected, $section->getPath());
    }

    public function testCanReturnConfigSectionsWhenCreatedFromNestedArray() : void
    {
        $values = [
            'one' => '1',
            'section' => [
                'two' => '2'
            ]
        ];

        $config = ArrayConfiguration::from($values);

        $sections = iterator_to_array($config->getChildren());

        $this->assertCount(1, $sections);

        $this->assertTrue($sections[0] instanceof ConfigurationSectionInterface);
    }

    public function testCreatingFromArrayWithNonStringKeysThrowsInvalidArgumentException() : void
    {
        $this->expectException(InvalidArgumentException::class);

        $values = [0 => 'Hello, World!'];

        $config = ArrayConfiguration::from($values);
    }

    public function testCreatingFromArrayWithNonStringValuesThrowsInvalidArgumentException() : void
    {
        $values = ['one' => 1];

        $this->expectException(InvalidArgumentException::class);

        $config = ArrayConfiguration::from($values);
    }

    public function testSimpleArrayOfStringKeysToStringValuesReturnsZeroConfigSections() : void
    {
        $config = ArrayConfiguration::from(['one' => '1']);

        $sections = iterator_to_array($config->getChildren());

        $this->assertEmpty($sections);
    }
}