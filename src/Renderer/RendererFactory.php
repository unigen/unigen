<?php
declare(strict_types=1);

namespace UniGen\Renderer;

use UniGen\Config\Config;
use UniGen\Renderer\Plates\PlatesRenderer;

class RendererFactory
{
//    /**
//     * @param Config $config
//     *
//     * @return RendererInterface
//     * @throws RendererException
//     */
//    public function create(Config $config): RendererInterface
//    {
//        return new TwigRenderer($config);
//    }

    /**
     * // TODO change Config to template path
     * @param Config $config
     *
     * @return RendererInterface
     */
    public function create(Config $config): RendererInterface
    {
        return new PlatesRenderer($config);
    }
}
