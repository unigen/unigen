<?php
declare(strict_types=1);

namespace UniGen\Renderer\Twig;

use Twig\Environment;
use Twig\Error\Error;
use Twig\Error\LoaderError;
use Twig\Loader\FilesystemLoader;
use UniGen\Config\Config;
use UniGen\Config\Exception\ConfigException;
use UniGen\Renderer\Context;
use UniGen\Renderer\RendererException;
use UniGen\Renderer\RendererInterface;

class TwigRenderer implements RendererInterface
{
    /** @var Config */
    private $config;

    /** @var Environment */
    private $twig;

    /**
     * @param Config $config
     *
     * @throws RendererException
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->twig = $this->createTwig();
    }

    /**
     * @return Environment
     *
     * @throws RendererException
     */
    private function createTwig(): Environment
    {
        $twig = new Environment(new FilesystemLoader());
        $twig->addExtension(new ScalarValueMapperTwigFilter());

        /** @var FilesystemLoader $loader */
        $loader = $this->twig->getLoader();
        try {
            $loader->addPath(dirname($this->config->get('template')));
        } catch (LoaderError | ConfigException $exception) {
            throw new RendererException('Unable to load template directory.', 0, $exception);
        }

        return $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function render(Context $context): string
    {
        try {
            $content = $this->twig->render(
                basename($this->config->get('template')),
                ['context' => $context]
            );
        } catch (Error | ConfigException $exception) {
            throw new RendererException('SUT render failed.', 0 , $exception);
        }

        return $content;
    }
}
