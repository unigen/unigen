<?php
declare(strict_types=1);

namespace UniGen\Generator;

use UniGen\Config\Config;
use UniGen\Config\Exception\ConfigException;
use UniGen\Generator\Resolver\ClassNameResolver;
use UniGen\Generator\Resolver\NamespaceResolver;
use UniGen\Generator\Resolver\PathResolver;
use UniGen\Renderer\RendererFactory;
use UniGen\Sut\SutFactory;

class GeneratorFactory
{
    /** @var SutFactory */
    private $sutFactory;

    /** @var RendererFactory */
    private $rendererFactory;

    /**
     * @param SutFactory $sutFactory
     * @param RendererFactory $rendererFactory
     */
    public function __construct(SutFactory $sutFactory, RendererFactory $rendererFactory)
    {
        $this->sutFactory = $sutFactory;
        $this->rendererFactory = $rendererFactory;
    }

    /**
     * @param Config $config
     *
     * @return TestGenerator
     *
     * @throws ConfigException
     */
    public function create(Config $config): TestGenerator
    {
        return new TestGenerator(
            $config,
            $this->sutFactory,
            $this->rendererFactory->create($config),
            new NamespaceResolver(),
            new PathResolver(),
            new ClassNameResolver()
        );
    }
}
