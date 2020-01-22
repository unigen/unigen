<?php

namespace UniGen\Generator;

use UniGen\Config\Config;
use UniGen\Config\Exception\ConfigException;
use UniGen\Generator\Exception\GeneratorException;
use UniGen\Generator\Resolver\ClassNameResolver;
use UniGen\Generator\Resolver\NamespaceResolver;
use UniGen\Generator\Resolver\PathResolver;
use UniGen\Renderer\Context;
use UniGen\Renderer\Exception\RendererException;
use UniGen\Renderer\RendererInterface;
use UniGen\Sut\Exception\SutException;
use UniGen\Sut\SutFactory;
use UniGen\Sut\SutInterface;
use UniGen\Util\Exception\FileWriterException;
use UniGen\Util\FileWriter;

class TestGenerator
{
    /** @var Config */
    private $config;

    /** @var SutFactory */
    private $sutFactory;

    /** @var RendererInterface */
    private $renderer;

    /** @var NamespaceResolver */
    private $namespaceResolver;

    /** @var PathResolver */
    private $pathResolver;

    /** @var ClassNameResolver */
    private $classNameResolver;

    /**
     * @param Config $config
     * @param SutFactory $sutFactory
     * @param RendererInterface $renderer
     * @param NamespaceResolver $namespaceResolver
     * @param PathResolver $pathResolver
     * @param ClassNameResolver $classNameResolver
     */
    public function __construct(
        Config $config,
        SutFactory $sutFactory,
        RendererInterface $renderer,
        NamespaceResolver $namespaceResolver,
        PathResolver $pathResolver,
        ClassNameResolver $classNameResolver
    ) {
        $this->config = $config;
        $this->sutFactory = $sutFactory;
        $this->renderer = $renderer;
        $this->namespaceResolver = $namespaceResolver;
        $this->pathResolver = $pathResolver;
        $this->classNameResolver = $classNameResolver;
    }

    /**
     * @param string $sourceFile
     * @param bool $override
     *
     * @return TestGenerationResult
     *
     * @throws ConfigException
     * @throws GeneratorException
     * @throws RendererException
     * @throws SutException
     */
    public function generate(string $sourceFile, bool $override): TestGenerationResult
    {
        $sut = $this->retrieveSut($sourceFile);

        $testNamespace = $this->namespaceResolver->resolve(
            $this->config->get('testNamespace'),
            $sut->getNamespace()
        );
        $content = $this->renderer->render(new Context($this->config, $sut, $testNamespace));
        $testPath = $this->pathResolver->resolve(
            $this->config->get('testPath'),
            $sourceFile
        );

        try {
            (new FileWriter())->write($testPath, $content, $override);
        } catch (FileWriterException $exception) {
            throw new GeneratorException(
                sprintf('Unable to persist test file "%s".', $testPath),
                0,
                $exception
            );
        }
        return new TestGenerationResult($testPath);
    }

    /**
     * @param string $path
     *
     * @return SutInterface
     *
     * @throws GeneratorException
     * @throws SutException
     */
    private function retrieveSut(string $path): SutInterface
    {
        $className = $this->classNameResolver->resolveFromFile($path);

        $sut = $this->sutFactory->create($className);
        (new SutValidator())->validate($sut);

        return $sut;
    }
}
