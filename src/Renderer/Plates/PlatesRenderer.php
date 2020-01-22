<?php
declare(strict_types=1);

namespace UniGen\Renderer\Plates;

use League\Plates\Engine;
use UniGen\Config\Config;
use UniGen\Config\Exception\ConfigException;
use UniGen\Renderer\Context;
use UniGen\Renderer\RendererInterface;
use UniGen\Renderer\ScalarValueMapper;

class PlatesRenderer implements RendererInterface
{
    /** @var Config */
    private $config;

    /** @var Engine */
    private $engine;

    /**
     * @param Config $config
     *
     * @throws ConfigException
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->engine = $this->createEngine();
    }

    /**
     * {@inheritdoc}
     *
     * @throws ConfigException
     */
    public function render(Context $context): string
    {
        $tplInfo = pathinfo($this->config->get('template'));

        return $this->engine->render(
            $tplInfo['filename'],
            ['context' => $context]
        );
    }

    /**
     * @return Engine
     *
     * @throws ConfigException
     */
    private function createEngine(): Engine
    {
        $engine = new Engine(dirname($this->config->get('template')), 'phtml');
        $engine->loadExtension(new ScalarValueMapperExtension(new ScalarValueMapper()));

        return $engine;
    }
}
