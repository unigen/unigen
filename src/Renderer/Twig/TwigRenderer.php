<?php

declare(strict_types=1);

namespace UniGen\Renderer\Twig;

use Twig\Environment;
use Twig\Error\Error;
use Twig\Error\LoaderError;
use Twig\Loader\FilesystemLoader;
use UniGen\Config\Config;
use UniGen\Config\Exception\UnknownConfigKeyException;
use UniGen\Renderer\Context;
use UniGen\Renderer\Exception\RenderException;
use UniGen\Renderer\Exception\TemplateLoadingException;
use UniGen\Renderer\RendererInterface;

class TwigRenderer implements RendererInterface
{
    private const SUT = 'sut';
    private const CONFIG = 'config';
    private const TEST_NAMESPACE = 'testNamespace';

    /** @var Environment */
    private $twig;

    /** @var Config */
    private $config;

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
     * {@inheritdoc}
     */
    public function render(Context $context): string
    {
        try {
            $this->applyTemplatePath();
        } catch (LoaderError | UnknownConfigKeyException $exception) {
            throw new TemplateLoadingException('Unable to load template directory.', 0, $exception);
        }

        try {
            $content = $this->twig->render(
                basename($this->config->get('template')),
                [
                    self::SUT => $context->getSut(),
                    self::CONFIG => $this->config,
                    self::TEST_NAMESPACE => $context->getTestNamespace()
                ]
            );
        } catch (Error | UnknownConfigKeyException $exception) {
            throw new RenderException('SUT render failed.', 0 , $exception);
        }

        return $content;
    }

    /**
     * @throws LoaderError
     * @throws UnknownConfigKeyException
     */
    private function applyTemplatePath(): void
    {
        /** @var FilesystemLoader $loader */
        $loader = $this->twig->getLoader();
        $loader->addPath(dirname($this->config->get('template')));
    }
}
