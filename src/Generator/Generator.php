<?php

namespace UniGen\Generator;

use UniGen\Config\Config;
use UniGen\Config\Exception\ConfigException;
use UniGen\Generator\GeneratorException;
use UniGen\Generator\Resolver\ClassNameResolver;
use UniGen\Generator\Resolver\NamespaceResolver;
use UniGen\Generator\Resolver\PathResolver;
use UniGen\Renderer\Context;
use UniGen\Renderer\RendererException;
use UniGen\Renderer\RendererInterface;
use UniGen\Sut\SutException;
use UniGen\Sut\SutFactory;
use UniGen\Sut\SutInterface;
use UniGen\Util\Exception\FileWriterException;
use UniGen\Util\FileWriter;

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
    public function __construct(Config $config, SutFactory $sutFactory, RendererInterface $renderer) {
        $this->sutFactory = $sutFactory;
        $this->renderer = $renderer;
        $this->config = $config;
    }

    /**
     * @param string $sourceFile
     * @param bool $override
     *
     * @return Result
     *
     * @throws ConfigException
     * @throws GeneratorException
     * @throws RendererException
     * @throws SutException
     */
    public function generate(string $sourceFile, bool $override): Result
    {
        $sut = $this->retrieveSut($sourceFile);

        $testNamespace = (new NamespaceResolver($this->config->get('testNamespace')))->resolve($sut->getNamespace());
        $content = $this->renderer->render(new Context($sut, $testNamespace));
        $testPath = (new PathResolver($this->config->get('testPath')))->resolve($sourceFile);

        try {
            (new FileWriter())->write($testPath, $content, $override);
        } catch (FileWriterException $exception) {
            throw new GeneratorException(
                'Unable to persist test file.',
                0,
                $exception
            );
        }
        return new Result($testPath);
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
        $className = (new ClassNameResolver())->resolveFromFile($path);

        $sut = $this->sutFactory->create($className);
        (new SutValidator())->validate($sut);

        return $sut;
    }
}
