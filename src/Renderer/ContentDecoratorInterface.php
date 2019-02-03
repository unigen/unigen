<?php

namespace UniGen\Renderer;

interface ContentDecoratorInterface
{
    /**
     * @param string $content
     *
     * @return string
     */
    public function decorate(string $content): string;
}
