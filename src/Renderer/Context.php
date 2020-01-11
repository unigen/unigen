<?php
declare(strict_types=1);

namespace UniGen\Renderer;

use UniGen\Sut\SutInterface;

class Context
{
    /** @var SutInterface */
    private $sut;

    /** @var string */
    private $testNamespace;

    /**
     * @param SutInterface $sut
     * @param string $testNamespace
     */
    public function __construct(SutInterface $sut, string $testNamespace)
    {
        $this->sut = $sut;
        $this->testNamespace = $testNamespace;
    }

    /**
     * @return SutInterface
     */
    public function getSut(): SutInterface
    {
        return $this->sut;
    }

    /**
     * @return string
     */
    public function getTestNamespace(): string
    {
        return $this->testNamespace;
    }
}
