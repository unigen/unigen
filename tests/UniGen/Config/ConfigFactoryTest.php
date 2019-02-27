<?php

namespace UniGen\Test\Config;

use UniGen\Config\Config;
use PHPUnit\Framework\TestCase;
use UniGen\Config\ConfigFactory;

class ConfigFactoryTest extends TestCase
{
    public function testShouldReturnDefaultConfig()
    {
        $this->assertInstanceOf(Config::class, ConfigFactory::createDefault());
    }
}
