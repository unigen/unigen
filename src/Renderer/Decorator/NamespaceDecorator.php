<?php

declare(strict_types=1);

namespace UnitGen\Renderer\Decorator;

use UnitGen\Config;
use UnitGen\Renderer\ContentDecoratorInterface;

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
