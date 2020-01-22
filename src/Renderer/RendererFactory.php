<?php
declare(strict_types=1);

namespace UniGen\Renderer;

use UniGen\Config\Config;
use UniGen\Config\Exception\ConfigException;
use UniGen\Renderer\Plates\PlatesRenderer;

class RendererFactory
{
    /**
     * @param Config $config
     *
     * @return RendererInterface
     *
     * @throws ConfigException
     */
    public function create(Config $config): RendererInterface
    {
        return new PlatesRenderer($config);
    }
}
