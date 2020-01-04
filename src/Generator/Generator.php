<?php

namespace UniGen\Generator;

use UniGen\Renderer\RendererInterface;
use UniGen\Sut\Exception\GeneratorException;
use UniGen\Sut\SutInterface;
use UniGen\Sut\SutProviderInterface;
use UniGen\Util\ClassNameResolver;

// TODO this class is doing too much
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
     * @throws GeneratorException
     */
    public function generate(string $sourceFile): Result
    {
        $sut = $this->retrieveSut($sourceFile);
        $this->validateSut($sut);



        $content = $this->renderer->render($sut);

        $testPath = 'file.php';
        file_put_contents($testPath, $content);

        return new Result($testPath);
    }

    /**
     * @param SutInterface $sut
     *
     * @throws GeneratorException
     */
    public function validateSut(SutInterface $sut)
    {
        if ($sut->isAbstract()) {
            throw new GeneratorException(sprintf('SUT cannot be an abstract class "%s".', $sut->getName()));
        }

        if ($sut->isAbstract()) {
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
        return $this->sutProvider->provide(ClassNameResolver::resolve(file_get_contents($path)));
    }
}
