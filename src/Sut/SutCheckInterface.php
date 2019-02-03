<?php

declare(strict_types=1);

namespace UnitGen\Sut;

interface SutCheckInterface
{
    /**
     * @param SutInterface $sut
     *
     * @return string
     */
    public function message(SutInterface $sut): string;

    /**
     * @param SutInterface $sut
     *
     * @return bool
     */
    public function appliesTo(SutInterface $sut): bool;
}
