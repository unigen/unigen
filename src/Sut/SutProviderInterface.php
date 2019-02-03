<?php

namespace UniGen\Sut;

use UniGen\Sut\Exception\SutValidatorException;

interface SutProviderInterface
{
    /**
     * @param string $class
     *
     * @throws SutValidatorException
     *
     * @return SutInterface
     */
    public function provide(string $class): SutInterface;
}
