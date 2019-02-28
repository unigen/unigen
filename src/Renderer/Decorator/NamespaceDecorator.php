<?php

declare(strict_types=1);

namespace UniGen\Renderer\Decorator;

use UniGen\Config\Config;
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
        $content = preg_replace(
            $this->config->get('namespacePattern'),
            $this->config->get('namespacePatternReplacement'),
            $content
        );

        if (is_null($content)) {
            throw new \InvalidArgumentException("Given namespace patterns are invalid");
        }

        return $content;
    }
}
