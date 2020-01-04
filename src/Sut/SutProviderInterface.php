<?php

namespace UniGen\Sut;

use UniGen\Sut\Exception\GeneratorException;

interface SutProviderInterface
{
    /**
     * @param string $class
     *
     * @return SutInterface
     *@throws GeneratorException
     *
     */
    public function provide(string $class): SutInterface;
}
