<?php

declare(strict_types=1);

namespace UnitGen\Sut\Check;

use UnitGen\Sut\SutCheckInterface;
use UnitGen\Sut\SutInterface;

class InterfaceCheck implements SutCheckInterface
{
    /**
     * {@inheritdoc}
     */
    public function message(SutInterface $sut): string
    {
        return "SUT cannot be an interface {$sut->getName()}";
    }

    /**
     * {@inheritdoc}
     */
    public function appliesTo(SutInterface $sut): bool
    {
        return $sut->isInterface();
    }
}
