<?php
declare(strict_types=1);

namespace UniGen\Renderer;

use UniGen\Config\Config;
use UniGen\Sut\SutInterface;

class Context
{
    /** @var Config */
    private $config;

    /** @var SutInterface */
    private $sut;

    /** @var string */
    private $testNamespace;

    /**
     * @param Config $config
     * @param SutInterface $sut
     * @param string $testNamespace
     */
    public function __construct(Config $config, SutInterface $sut, string $testNamespace)
    {
        $this->sut = $sut;
        $this->testNamespace = $testNamespace;
        $this->config = $config;
    }

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
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
