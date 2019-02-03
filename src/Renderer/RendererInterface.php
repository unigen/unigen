<?php

namespace UniGen\Renderer;

use UniGen\Sut\SutInterface;

interface RendererInterface
{
    /**
     * @param SutInterface $sut
     *
     * @return string
     */
    public function render(SutInterface $sut): string;
}
