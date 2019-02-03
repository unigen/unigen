<?php

declare(strict_types=1);

namespace UnitGen\Renderer;

use UnitGen\Config;
use Twig_Environment;
use UnitGen\Sut\SutInterface;

class TwigRenderer implements RendererInterface
{
    private const SUT = 'sut';
    private const CONFIG = 'config';

    /** @var Twig_Environment */
    private $twig;

    /** @var string */
    private $template;

    /** @var Config */
    private $config;

    /** @var ContentDecoratorInterface[] */
    private $decorators = [];

    /**
     * @param string           $template
     * @param Twig_Environment $twig
     * @param Config           $config
     */
    public function __construct(string $template, Twig_Environment $twig, Config $config)
    {
        $this->twig = $twig;
        $this->config = $config;
        $this->template = $template;
    }

    /**
     * @param ContentDecoratorInterface $decorator
     */
    public function addDecorator(ContentDecoratorInterface $decorator)
    {
        $this->decorators[] = $decorator;
    }

    /**
     * {@inheritdoc}
     */
    public function render(SutInterface $sut): string
    {
        $content = $this->twig->render($this->template, [
            self::SUT => $sut,
            self::CONFIG => $this->config
        ]);

        foreach ($this->decorators as $decorator) {
            $content = $decorator->decorate($content);
        }

        return $content;
    }
}
