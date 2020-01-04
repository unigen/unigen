<?php
declare(strict_types=1);

namespace UniGen\Generator;

use UniGen\Config\Config;
use UniGen\Renderer\RendererFactory;
use UniGen\Sut\Provider\ReflectionSutProvider;

class GeneratorFactory
{
    /** @var RendererFactory */
    private $rendererFactory;

    /**
     * @param RendererFactory $rendererFactory
     */
    public function __construct(RendererFactory $rendererFactory)
    {
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
            new ReflectionSutProvider(),
            $this->rendererFactory->create($config)
        );
    }
}
