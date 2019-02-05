<?php

declare(strict_types=1);

namespace UniGen\Test\Util;

use PHPUnit\Framework\TestCase;
use UniGen\Util\ScalarValueResolver;

class ScalarValueResolverTest extends TestCase
{
    public function testResolveShouldReturnString()
    {
        $this->assertEquals('string', ScalarValueResolver::resolve('string'));
    }

    public function testResolveShouldReturnInt()
    {
        $this->assertEquals(1, ScalarValueResolver::resolve('int'));
    }

    public function testResolveShouldReturnTrue()
    {
        $this->assertEquals(true, ScalarValueResolver::resolve('bool'));
    }

    public function testResolveShouldReturnCallback()
    {
        $this->assertEquals(function () {}, ScalarValueResolver::resolve('callable'));
    }

    public function testResolveShouldReturnArray()
    {
        $this->assertEquals([], ScalarValueResolver::resolve('array'));
    }

    public function testResolveShouldReturnMixedWhenTypeIsUnknown()
    {
        $this->assertEquals('mixed', ScalarValueResolver::resolve('invalid'));
    }

    public function testResolveShouldReturnMixedWhenTypeIsFloat()
    {
        $this->assertEquals(0.0, ScalarValueResolver::resolve('float'));
    }
}
