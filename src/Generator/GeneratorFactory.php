<?php
declare(strict_types=1);

namespace UniGen\Generator;

use UniGen\Config\Config;
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
     * @return Generator
     */
    public function create(Config $config): Generator
    {
        return new Generator(
            $this->sutFactory,
            $this->rendererFactory->create($config)
        );
    }
}
