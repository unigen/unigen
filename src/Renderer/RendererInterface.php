<?php

namespace UniGen\Renderer;

use UniGen\Renderer\Exception\RendererException;
use UniGen\Sut\SutInterface;

interface RendererInterface
{
    /**
     * @param SutInterface $sut
     *
     * @return string
     *
     * @throws RendererException
     */
    public function render(SutInterface $sut): string;
}
