<?php

namespace UniGen\Test\Renderer;

use Mockery;
use Twig_Environment;
use UniGen\Config\Config;
use Mockery\MockInterface;
use UniGen\Sut\SutInterface;
use PHPUnit\Framework\TestCase;
use UniGen\Renderer\TwigRenderer;
use Twig\Loader\FilesystemLoader;
use UniGen\Renderer\ContentDecoratorInterface;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class TwigRendererTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    const CONTENT = 'content';
    const TEMPLATE = 'template';
    const CONTENT_DECORATED = 'decoratedContent';

    /** @var Twig_Environment|MockInterface */
    private $twigMock;

    /** @var Config|MockInterface */
    private $configMock;

    /** @var SutInterface|MockInterface */
    private $sutMock;

    /** @var FilesystemLoader|MockInterface */
    private $loaderMock;

    /** @var TwigRenderer */
    private $sut;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->configMock = Mockery::mock(Config::class);
        $this->sutMock = Mockery::mock(SutInterface::class);
        $this->twigMock = Mockery::mock(Twig_Environment::class);
        $this->loaderMock = Mockery::mock(FilesystemLoader::class);

        $this->sut = new TwigRenderer(
            $this->twigMock,
            $this->configMock
        );
    }

    public function testRenderShouldSuccessfullyRenderContent()
    {
        $this->twigMock
            ->shouldReceive('getLoader')
            ->andReturn($this->loaderMock);

        $this->loaderMock
            ->shouldReceive('addPath')
            ->with('pathDir');

        $this->configMock
            ->shouldReceive('get')
            ->with('templateDir')
            ->andReturn('pathDir');

        $this->configMock
            ->shouldReceive('get')
            ->with('template')
            ->andReturn(self::TEMPLATE);

        $this->twigMock
            ->shouldReceive('render')
            ->with(self::TEMPLATE, [
                'sut' => $this->sutMock,
                'config' => $this->configMock,
            ])
            ->andReturn(self::CONTENT);

        $this->assertEquals(self::CONTENT, $this->sut->render($this->sutMock));
    }

    public function testRenderShouldReturnDecoratedContent()
    {
        $this->twigMock
            ->shouldReceive('getLoader')
            ->andReturn($this->loaderMock);

        $this->loaderMock
            ->shouldReceive('addPath')
            ->with('pathDir');

        $this->configMock
            ->shouldReceive('get')
            ->with('templateDir')
            ->andReturn('pathDir');

        $this->configMock
            ->shouldReceive('get')
            ->with('template')
            ->andReturn(self::TEMPLATE);

        $this->twigMock
            ->shouldReceive('render')
            ->with(self::TEMPLATE, [
                'sut' => $this->sutMock,
                'config' => $this->configMock,
            ])
            ->andReturn(self::CONTENT);

        $decorator = Mockery::mock(ContentDecoratorInterface::class);

        $decorator
            ->shouldReceive('decorate')
            ->once()
            ->with(self::CONTENT)
            ->andReturn(self::CONTENT_DECORATED);

        $this->sut->addDecorator($decorator);

        $this->assertEquals(self::CONTENT_DECORATED, $this->sut->render($this->sutMock));
    }
}
