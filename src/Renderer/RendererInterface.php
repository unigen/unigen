<?php

namespace UniGen\Renderer;

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
