<?php

namespace UniGen\Generator;

use UniGen\Config\Config;
use UniGen\Generator\Resolver\ClassNameResolver;
use UniGen\Generator\Resolver\NamespaceResolver;
use UniGen\Generator\Resolver\PathResolver;
use UniGen\Renderer\Context;
use UniGen\Renderer\RendererInterface;
use UniGen\Sut\Exception\GeneratorException;
use UniGen\Sut\SutFactory;
use UniGen\Sut\SutInterface;

// TODO this class is doing too much
class Generator
{
    /** @var Config */
    private $config;

    /** @var SutFactory */
    private $sutFactory;

    /** @var RendererInterface */
    private $renderer;

    /**
     * @param Config $config
     * @param SutFactory $sutFactory
     * @param RendererInterface $renderer
     */
    public function __construct(
        Config $config,
        SutFactory $sutFactory,
        RendererInterface $renderer
    ) {
        $this->sutFactory = $sutFactory;
        $this->renderer = $renderer;
        $this->config = $config;
    }

    /**
     * @param string $sourceFile
     *
     * @return Result
     * @throws GeneratorException
     */
    public function generate(string $sourceFile): Result
    {
        $sut = $this->retrieveSut($sourceFile);
        (new SutValidator())->validate($sut);

        $testNamespace = (new NamespaceResolver($this->config->get('testNamespace')))->resolve($sut->getNamespace());

        $content = $this->renderer->render(new Context($sut, $testNamespace));

        $testPath = (new PathResolver($this->config->get('testPath')))->resolve($sourceFile);
        // TODO move to FileWriter

        mkdir(dirname($testPath), 0777, true);
        file_put_contents($testPath, $content);

        return new Result($testPath);
    }

    /**
     * @param string $path
     *
     * @return SutInterface
     * @throws GeneratorException
     */
    private function retrieveSut(string $path): SutInterface
    {
        $className = (new ClassNameResolver())->resolveFromFile($path);

        $sut = $this->sutFactory->create($className);
        $this->validateSut($sut);

        return $sut;
    }
}
