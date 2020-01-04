<?php
declare(strict_types=1);

namespace UniGen\Renderer;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use UniGen\Config\Config;
use UniGen\Renderer\Twig\ScalarValueMapperTwigFilter;
use UniGen\Renderer\Twig\TwigRenderer;

class RendererFactory
{
    /**
     * @param Config $config
     *
     * @return RendererInterface
     */
    public function create(Config $config): RendererInterface
    {
        return new TwigRenderer($this->createTwig(), $config);
    }

    /**
     * @return Environment
     */
    private function createTwig(): Environment
    {
        $twig = new Environment(new FilesystemLoader());
        $twig->addExtension(new ScalarValueMapperTwigFilter());

        return $twig;
    }
}