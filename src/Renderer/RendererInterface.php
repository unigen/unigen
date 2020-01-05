<?php

namespace UniGen\Renderer;

use UniGen\Renderer\Exception\RendererException;

interface RendererInterface
{
    /**
     * @param Context $context
     *
     * @return string
     *
     * @throws RendererException
     */
    public function render(Context $context): string;
}
