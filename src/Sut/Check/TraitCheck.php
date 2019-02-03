<?php

declare(strict_types=1);

namespace UniGen\Sut\Check;

use UniGen\Sut\SutInterface;
use UniGen\Sut\SutCheckInterface;

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
