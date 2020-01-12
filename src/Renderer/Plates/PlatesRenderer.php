<?php
declare(strict_types=1);

namespace UniGen\Renderer\Plates;

use League\Plates\Engine;
use UniGen\Config\Config;
use UniGen\Renderer\Context;
use UniGen\Renderer\RendererInterface;
use UniGen\Renderer\ScalarValueMapper;

class PlatesRenderer implements RendererInterface
{
    /** @var Config */
    private $config;

    /** @var Engine */
    private $engine;

    public function __construct(Config $config)
    {
        $this->config = $config;
        // TODO to method
        $this->engine = new Engine(dirname($this->config->get('template')), 'phtml');
        $this->engine->loadExtension(new ScalarValueMapperExtension(new ScalarValueMapper()));
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
