<?php
declare(strict_types=1);

namespace UniGen\Generator;

use UniGen\Generator\Exception\WrongSutException;
use UniGen\Sut\SutInterface;

class SutValidator
{
    /**
     * @param SutInterface $sut
     *
     * @throws WrongSutException
     */
    public function validate(SutInterface $sut)
    {
        if ($sut->isAbstract()) {
            throw new WrongSutException(sprintf('SUT cannot be an abstract class "%s".', $sut->getName()));
        }

        if ($sut->isInterface()) {
            throw new WrongSutException(sprintf('SUT cannot be an interface "%s".', $sut->getName()));
        }

        if ($sut->isTrait()) {
            throw new WrongSutException(sprintf('SUT cannot be a trait "%s".', $sut->getName()));
        }
    }
}