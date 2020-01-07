<?php

namespace UniGen\Generator;

use UniGen\Config\Config;
use UniGen\Config\Exception\UnknownConfigKeyException;
use UniGen\Generator\Exception\NoClassNameException;
use UniGen\Generator\Exception\NoResolverSourceException;
use UniGen\Generator\Exception\TestPersistException;
use UniGen\Generator\Exception\UnknownResolverPatternException;
use UniGen\Generator\Exception\WrongSutException;
use UniGen\Generator\Resolver\ClassNameResolver;
use UniGen\Generator\Resolver\NamespaceResolver;
use UniGen\Generator\Resolver\PathResolver;
use UniGen\Renderer\Context;
use UniGen\Renderer\RendererException;
use UniGen\Renderer\RendererInterface;
use UniGen\Sut\Exception\ClassNotExistException;
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
     *
     * @throws ClassNotExistException
     * @throws UnknownResolverPatternException
     * @throws WrongSutException
     * @throws NoClassNameException
     * @throws NoResolverSourceException
     * @throws TestPersistException
     * @throws UnknownConfigKeyException
     * @throws RendererException
     */
    public function generate(string $sourceFile): Result
    {
        $sut = $this->retrieveSut($sourceFile);
        (new SutValidator())->validate($sut);

        $testNamespace = (new NamespaceResolver($this->config->get('testNamespace')))->resolve($sut->getNamespace());
        $content = $this->renderer->render(new Context($sut, $testNamespace));
        $testPath = (new PathResolver($this->config->get('testPath')))->resolve($sourceFile);

        try {
            (new FileWriter())->write($testPath, $content);
        } catch (FileWriterException $exception) {
            throw new TestPersistException(
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
     * @throws NoClassNameException
     * @throws NoResolverSourceException
     * @throws ClassNotExistException
     */
    private function retrieveSut(string $path): SutInterface
    {
        $className = (new ClassNameResolver())->resolveFromFile($path);

        $sut = $this->sutFactory->create($className);
        $this->validateSut($sut);

        return $sut;
    }
}
