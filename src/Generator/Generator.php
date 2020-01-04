<?php

namespace UniGen\Generator;

use UniGen\Renderer\RendererInterface;
use UniGen\Sut\Exception\SutValidatorException;
use UniGen\Sut\SutInterface;
use UniGen\Sut\SutProviderInterface;
use UniGen\Util\ClassNameResolver;

class Generator
{
    /** @var SutProviderInterface */
    private $sutProvider;

    /** @var RendererInterface */
    private $renderer;

    /**
     * @param SutProviderInterface $sutProvider
     * @param RendererInterface $renderer
     */
    public function __construct(
        SutProviderInterface $sutProvider,
        RendererInterface $renderer
    ) {
        $this->sutProvider = $sutProvider;
        $this->renderer = $renderer;
    }

    /**
     * @param string $sourceFile
     *
     * @return Result
     * @throws SutValidatorException
     */
    public function generate(string $sourceFile): Result
    {
        $sut = $this->retrieveSut($sourceFile);
        $content = $this->renderer->render($sut);

        $testPath = 'file.php';
        file_put_contents($testPath, $content);

        return new Result($testPath);
    }

    /**
     * @param string $path
     *
     * @return SutInterface
     * @throws SutValidatorException
     */
    private function retrieveSut(string $path): SutInterface
    {
        echo ClassNameResolver::resolve(file_get_contents($path));
        die();

        return $this->sutProvider->provide(ClassNameResolver::resolve(file_get_contents($path)));
    }
}
