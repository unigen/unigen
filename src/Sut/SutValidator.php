<?php

declare(strict_types=1);

namespace UniGen\Sut;

use UniGen\Sut\Exception\SutValidatorException;

class SutValidator
{
    /** @var SutCheckInterface[] */
    private $checks = [];

    /**
     * @param SutCheckInterface $check
     */
    public function addCheck(SutCheckInterface $check)
    {
        $this->checks[] = $check;
    }

    /**
     * @param SutInterface $sut
     *
     * @throws SutValidatorException
     */
    public function validate(SutInterface $sut)
    {
        foreach ($this->checks as $check) {
            if ($check->appliesTo($sut)) {
                throw new SutValidatorException($check->message($sut));
            }
        }
    }
}
