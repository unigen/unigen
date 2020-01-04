<?php


namespace UniGen\Generator;


use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig_Environment;
use Twig_Loader_Filesystem;
use UniGen\Config\Config;
use UniGen\Renderer\Decorator\NamespaceDecorator;
use UniGen\Renderer\TwigRenderer;
use UniGen\Sut\Check\AbstractCheck;
use UniGen\Sut\Check\InterfaceCheck;
use UniGen\Sut\Check\TraitCheck;
use UniGen\Sut\Provider\ReflectionSutProvider;
use UniGen\Sut\SutValidator;
use UniGen\Util\ScalarValueMapperTwigFilter;

class GeneratorFactory
{
    public function create(Config $config): Generator
    {
        return new Generator(
            $this->createSutProvider(),
            $this->createRenderer($config)
        );
    }

    /**
     * @return SutValidator
     */
    private function createSutValidator(): SutValidator
    {
        $validator = new SutValidator();
        $validator->addCheck(new TraitCheck());
        $validator->addCheck(new AbstractCheck());
        $validator->addCheck(new InterfaceCheck());

        return $validator;
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

    /**
     * @param Config $config
     *
     * @return TwigRenderer
     */
    private function createRenderer(Config $config)
    {
        $renderer = new TwigRenderer($this->createTwig(), $config);
        $renderer->addDecorator(new NamespaceDecorator($config));

        return $renderer;
    }

    /**
     * @return ReflectionSutProvider
     */
    private function createSutProvider()
    {
        return new ReflectionSutProvider($this->createSutValidator());
    }
}
