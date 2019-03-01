<?php

namespace UniGen\Test\Config;

use UniGen\Config\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testGetShouldReturnCorrectParametersPassedThroughConstructor()
    {
        $config = new Config(['first' => 1, 'second' => [1, 2, 3]]);

        $this->assertEquals(1, $config->get('first'));
        $this->assertEquals([1, 2, 3], $config->get('second'));
    }

    public function testGetShouldReturnDefaultValueWhenConfigIsCreatedWithoutParameters()
    {
        $config = new Config();

        $this->assertNull($config->get('first'));
    }

    public function testMergeShouldMergeCorrectValues()
    {
        $config = new Config(['first' => 1, 'second' => [1, 2, 3]]);

        $config->merge(['second' => 2, 'other' => 3]);

        $this->assertEquals(1, $config->get('first'));
        $this->assertEquals(2, $config->get('second'));
        $this->assertEquals(3, $config->get('other'));
    }

    public function testMergeEmptyArrayShouldReturnCorrectValues()
    {
        $config = new Config(['first' => 1, 'second' => 2]);

        $config->merge([]);

        $this->assertEquals(1, $config->get('first'));
        $this->assertEquals(2, $config->get('second'));
    }

    public function testMergeValuesToEmptyConfigShouldReturnCorrectValues()
    {
        $config = new Config();

        $config->merge(['first' => 1, 'second' => 2]);

        $this->assertEquals(1, $config->get('first'));
        $this->assertEquals(2, $config->get('second'));
    }

    public function testSetShouldSecValuesCorrectly()
    {
        $config = new Config();

        $config->set('first', 1);
        $config->set('second', [1, 2, 3]);

        $this->assertEquals(1, $config->get('first'));
        $this->assertEquals([1, 2, 3], $config->get('second'));
    }

    public function testSetShouldOverwrittenValuesWhenKeyExists()
    {
        $config = new Config(['first' => 0]);

        $config->set('first', 1);

        $this->assertEquals(1, $config->get('first'));
    }

    public function testGetShouldReturnNulLWhenKeyDoesNotExist()
    {
        $this->assertNull((new Config())->get('first'));
    }

    public function testGetShouldReturnDefaultValuesWhenKeyDoesNotExist()
    {
        $this->assertEquals('default', (new Config())->get('first', 'default'));
    }

    public function testHasShouldReturnFalseWhenKeyDoesNotExist()
    {
        $this->assertFalse((new Config())->has('first'));
    }

    public function testHasShouldReturnTrueWhenKeyExists()
    {
        $this->assertTrue((new Config(['first' => 1]))->has('first'));
    }
}
