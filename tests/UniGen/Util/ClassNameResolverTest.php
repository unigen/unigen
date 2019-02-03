<?php

namespace UnitGen\Test\Util;

use PHPUnit\Framework\TestCase;
use UnitGen\Util\ClassNameResolver;

class ClassNameResolverTest extends TestCase
{
    /** @var ClassNameResolver */
    private $sut;

    /**
     * {@inheritdoc}
    */
    public function setUp()
    {
        $this->sut = new ClassNameResolver();
    }

    public function testResolveShouldThrowExceptionWhenThereIsNoClassInContent()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Given file content must contains class syntax');

        $this->sut::resolve('empty');
    }

    public function testResolveShouldReturnCorrectClassName()
    {
        $this->assertEquals('\ExampleClass', $this->sut::resolve('<?php class ExampleClass'));
    }
}
