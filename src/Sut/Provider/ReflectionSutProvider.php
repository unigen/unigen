<?php

declare(strict_types=1);

namespace UnitGen\Sut\Provider;

use ReflectionClass;
use UnitGen\Sut\SutInterface;
use UnitGen\Sut\SutValidator;
use UnitGen\Sut\SutProviderInterface;
use UnitGen\Sut\Adapter\ReflectionSutAdapter;

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
