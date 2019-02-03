<?php

namespace UnitGen\Test\Renderer\Decorator;

use UnitGen\Config;
use Mockery;
use Mockery\MockInterface;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use UnitGen\Renderer\Decorator\NamespaceDecorator;

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

        $this->sut = new NamespaceDecorator(
            $this->config
        );
    }

    public function testDecorate()
    {
        $this->config
            ->shouldReceive('namespacePattern')
            ->andReturn('/(.+)/');

        $this->config
            ->shouldReceive('namespaceReplacePattern')
            ->andReturn('decorated${1}');

        $this->assertEquals('decoratedcontent', $this->sut->decorate('content'));
    }
}
