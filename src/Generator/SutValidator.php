<?php
declare(strict_types=1);

namespace UniGen\Generator;

use UniGen\Generator\Exception\GeneratorException;
use UniGen\Sut\SutInterface;

class SutValidator
{
    /**
     * @param SutInterface $sut
     *
     * @throws GeneratorException
     */
    public function validate(SutInterface $sut): void
    {
        if ($sut->isAbstract()) {
            throw new GeneratorException(sprintf('SUT cannot be an abstract class "%s".', $sut->getName()));
        }

        if ($sut->isInterface()) {
            throw new GeneratorException(sprintf('SUT cannot be an interface "%s".', $sut->getName()));
        }

        if ($sut->isTrait()) {
            throw new GeneratorException(sprintf('SUT cannot be a trait "%s".', $sut->getName()));
        }
    }
}