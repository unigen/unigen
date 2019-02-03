<?php

namespace UnitGen\Test\Renderer;

use Twig_Environment;
use UnitGen\Config;
use Mockery;
use Mockery\MockInterface;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use UnitGen\Renderer\ContentDecoratorInterface;
use UnitGen\Renderer\TwigRenderer;
use UnitGen\Sut\SutInterface;

class TwigRendererTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private const TEMPLATE = 'template';
    private const CONTENT = 'content';
    private const CONTENT_DECORATED = 'decoratedContent';

    /** @var string|MockInterface */
    private $template;

    /** @var Twig_Environment|MockInterface */
    private $twig;

    /** @var Config|MockInterface */
    private $testGeneratorConfig;

    /** @var SutInterface|MockInterface */
    private $sutMock;

    /** @var TwigRenderer */
    private $sut;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->template = self::TEMPLATE;
        $this->sutMock = Mockery::mock(SutInterface::class);
        $this->twig = Mockery::mock(Twig_Environment::class);
        $this->testGeneratorConfig = Mockery::mock(Config::class);

        $this->sut = new TwigRenderer(
            $this->template,
            $this->twig,
            $this->testGeneratorConfig
        );
    }

    public function testRenderShouldSuccessfullyRenderContent()
    {
        $this->twig
            ->shouldReceive('render')
            ->with(self::TEMPLATE, [
                'sut' => $this->sutMock,
                'config' => $this->testGeneratorConfig,
            ])
            ->andReturn(self::CONTENT);

        $this->assertEquals(self::CONTENT, $this->sut->render($this->sutMock));
    }

    public function testRenderShouldReturnDecoratedContent()
    {
        $this->twig
            ->shouldReceive('render')
            ->with(self::TEMPLATE, [
                'sut' => $this->sutMock,
                'config' => $this->testGeneratorConfig,
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
