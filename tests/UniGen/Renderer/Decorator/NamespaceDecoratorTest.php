<?php

namespace UniGen\Test\Renderer\Decorator;

use Mockery;
use Mockery\MockInterface;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use UniGen\Config\Config;
use UniGen\Renderer\Decorator\NamespaceDecorator;

class NamespaceDecoratorTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var Config|MockInterface */
    private $config;

    /** @var NamespaceDecorator */
    private $sut;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->config = Mockery::mock(Config::class);

        $this->sut = new NamespaceDecorator($this->config);
    }

    public function testDecorate()
    {
        $this->config
            ->shouldReceive('get')
            ->with('namespacePattern')
            ->andReturn('/(.+)/');

        $this->config
            ->shouldReceive('get')
            ->with('namespacePatternReplacement')
            ->andReturn('decorated${1}');

        $this->assertEquals('decoratedcontent', $this->sut->decorate('content'));
    }

    public function testDecorateShouldReturnUnchangedContentWhenThereIsNoMatch()
    {
        $this->config
            ->shouldReceive('get')
            ->with('namespacePattern')
            ->andReturn('/aaa/');

        $this->config
            ->shouldReceive('get')
            ->with('namespacePatternReplacement')
            ->andReturn('/ccc/');

        $this->assertEquals('content', $this->sut->decorate('content'));
    }

    public function testDecorateShouldThrowExceptionWhenPatternIsInvalid()
    {
        $this->config
            ->shouldReceive('get')
            ->with('namespacePattern')
            ->andReturn('/');

        $this->config
            ->shouldReceive('get')
            ->with('namespacePatternReplacement')
            ->andReturn('/');

        $this->expectExceptionMessage('Given namespace patterns are invalid');
        $this->expectException(\InvalidArgumentException::class);

        $this->assertEquals('content', $this->sut->decorate('content'));
    }
}
