<?php

declare(strict_types=1);

namespace UniGen\Renderer;

use Twig\Environment;
use Twig_Environment;
use UniGen\Config\Config;
use UniGen\Sut\SutInterface;
use Twig\Loader\FilesystemLoader;

class TwigRenderer implements RendererInterface
{
    const SUT = 'sut';
    const CONFIG = 'config';

    /** @var Environment */
    private $twig;

    /** @var Config */
    private $config;

    /** @var ContentDecoratorInterface[] */
    private $decorators = [];

    /**
     * @param Environment $twig
     * @param Config           $config
     */
    public function __construct(Environment $twig, Config $config)
    {
        $this->twig = $twig;
        $this->config = $config;
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
        $this->applyTemplatePath();

        $content = $this->twig->render(basename($this->config->getTemplate()), [
            self::SUT => $sut,
            self::CONFIG => $this->config
        ]);

        foreach ($this->decorators as $decorator) {
            $content = $decorator->decorate($content);
        }

        return $content;
    }

    private function applyTemplatePath()
    {
        /** @var FilesystemLoader $loader */
        $loader = $this->twig->getLoader();

        $loader->addPath(dirname($this->config->getTemplate()));
    }
}
