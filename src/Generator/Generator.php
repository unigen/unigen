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
        $testNamespace = (new NamespaceResolver($this->config->get('testNamespace')))->resolve($sut->getNamespace());

        $content = $this->renderer->render(new Context($sut, $testNamespace));

        $testPath = (new PathResolver($this->config->get('testPath')))->resolve($sourceFile); // 'file.php';
        file_put_contents($testPath, $content);

        return new Result($testPath);
    }

    /**
     * TODO move to different vclass
     * @param SutInterface $sut
     *
     * @throws GeneratorException
     */
    public function validateSut(SutInterface $sut)
    {
        if ($sut->isAbstract()) {
            throw new GeneratorException(sprintf('SUT cannot be an abstract class "%s".', $sut->getName()));
        }

        if ($sut->isInterface()) {
            throw new GeneratorException(sprintf('SUT cannot be an interface "%s".', $sut->getName()));
        }

        if ($sut->isTrait()) {
            throw new GeneratorException(sprintf('SUT cannot be a trait "%s".', $sut->getName()));
        }
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
