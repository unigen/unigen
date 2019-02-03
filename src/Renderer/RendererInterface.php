<?php

namespace UnitGen\Renderer;

use UnitGen\Sut\SutInterface;

interface RendererInterface
{
    /**
     * @param SutInterface $sut
     *
     * @return string
     */
    public function render(SutInterface $sut): string;
}
