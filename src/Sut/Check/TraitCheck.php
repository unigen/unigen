<?php

declare(strict_types=1);

namespace UnitGen\Sut\Check;

use UnitGen\Sut\SutInterface;
use UnitGen\Sut\SutCheckInterface;

class TraitCheck implements SutCheckInterface
{
    /**
     * {@inheritdoc}
     */
    public function message(SutInterface $sut): string
    {
        return "SUT cannot be a trait {$sut->getName()}";
    }

    /**
     * {@inheritdoc}
     */
    public function appliesTo(SutInterface $sut): bool
    {
        return $sut->isTrait();
    }
}
