<?php

declare(strict_types=1);

namespace UniGen;

use UniGen\Config\Config;
use UniGen\Sut\SutInterface;
use InvalidArgumentException;
use UniGen\Util\ClassNameResolver;
use UniGen\Sut\SutProviderInterface;
use UniGen\Renderer\RendererInterface;
use UniGen\FileSystem\FileSystemInterface;

class TestGenerator
{
    /** @var Config */
    private $config;

    /** @var RendererInterface */
    private $renderer;

    /** @var FileSystemInterface */
    private $fileSystem;

    /** @var SutProviderInterface */
    private $sutProvider;

    /**
     * @param Config               $config
     * @param RendererInterface    $renderer
     * @param FileSystemInterface  $fileSystem
     * @param SutProviderInterface $sutProvider
     */
    public function __construct(
        Config $config,
        RendererInterface $renderer,
        FileSystemInterface $fileSystem,
        SutProviderInterface $sutProvider
    ) {
        $this->config = $config;
        $this->renderer = $renderer;
        $this->fileSystem = $fileSystem;
        $this->sutProvider = $sutProvider;
    }


    /**
     * @param string $path
     *
     * @throws InvalidArgumentException
     */
    public function generate(string $path)
    {
        if (!$this->fileSystem->exist($path)) {
            throw new InvalidArgumentException("Class to test does not exist in path {$path}");
        }

        $sut = $this->retrieveSut($path);
        $testPath = $this->retrieveTestTargetPath($sut);

        if ($this->fileSystem->exist($testPath)) {
            throw new InvalidArgumentException("Test file {$testPath} already exist");
        }

        $this->fileSystem->write($testPath, $this->renderer->render($sut));
    }

    /**
     * @param string $path
     *
     * @return SutInterface
     */
    private function retrieveSut(string $path): SutInterface
    {
        return $this->sutProvider->provide(ClassNameResolver::resolve($this->fileSystem->read($path)));
    }

    /**
     * @param SutInterface $sut
     *
     * @return string
     */
    private function retrieveTestTargetPath(SutInterface $sut): string
    {
        return preg_replace(
            $this->config->get('pathPattern'),
            $this->config->get('pathPatternReplacement'),
            $sut->getPath()
        );
    }
}
