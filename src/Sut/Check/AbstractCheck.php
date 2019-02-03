<?php

declare(strict_types=1);

namespace UniGen\Sut\Check;

use UniGen\Sut\SutCheckInterface;
use UniGen\Sut\SutInterface;

class AbstractCheck implements SutCheckInterface
{
    /**
     * {@inheritdoc}
     */
    public function message(SutInterface $sut): string
    {
        return "SUT cannot be an abstract class {$sut->getName()}";
    }

    /**
     * {@inheritdoc}
     */
    public function appliesTo(SutInterface $sut): bool
    {
        return $sut->isAbstract();
    }
}
