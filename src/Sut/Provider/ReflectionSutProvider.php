<?php

declare(strict_types=1);

namespace UniGen\Sut\Provider;

use ReflectionClass;
use UniGen\Sut\SutInterface;
use UniGen\Sut\SutValidator;
use UniGen\Sut\SutProviderInterface;
use UniGen\Sut\Adapter\ReflectionSutAdapter;

class ReflectionSutProvider implements SutProviderInterface
{
    /** @var SutValidator */
    private $validator;

    /**
     * @param SutValidator $validator
     */
    public function __construct(SutValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function provide(string $class): SutInterface
    {
        $sut = new ReflectionSutAdapter(new ReflectionClass($class));

        $this->validator->validate($sut);

        return $sut;
    }
}
