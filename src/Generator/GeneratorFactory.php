<?php
declare(strict_types=1);

namespace UniGen\Generator;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use UniGen\Config\Config;
use UniGen\Renderer\TwigRenderer;
use UniGen\Sut\Provider\ReflectionSutProvider;
use UniGen\Util\ScalarValueMapperTwigFilter;

class GeneratorFactory
{
    /**
     * @param Config $config
     *
     * @return Generator
     */
    public function create(Config $config): Generator
    {
        return new Generator(
            new ReflectionSutProvider(),
            new TwigRenderer($this->createTwig(), $config)
        );
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
