<?php

declare(strict_types=1);

namespace UniGen\Renderer\Decorator;

use UniGen\Config;
use UniGen\Renderer\ContentDecoratorInterface;

class NamespaceDecorator implements ContentDecoratorInterface
{
    /** @var Config */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function decorate(string $content): string
    {
        return preg_replace(
            $this->config->namespacePattern(),
            $this->config->namespaceReplacePattern(),
            $content
        );
    }
}
