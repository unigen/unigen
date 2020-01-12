<?php
declare(strict_types=1);

namespace UniGen\Renderer\Plates;

use League\Plates\Engine;
use UniGen\Config\Config;
use UniGen\Renderer\Context;
use UniGen\Renderer\RendererInterface;

class PlatesRenderer implements RendererInterface
{
    /** @var Config */
    private $config;

    /** @var Engine */
    private $engine;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->engine = new Engine(dirname($this->config->get('template')), 'phtml');
    }

    /**
     * @inheritDoc
     */
    public function render(Context $context): string
    {
        $tplInfo = pathinfo($this->config->get('template'));

        return $this->engine->render(
            $tplInfo['filename'],
            ['context' => $context]
        );
    }
}
