<?php

declare(strict_types=1);

namespace UniGen\Sut\Provider;

use ReflectionClass;
use UniGen\Sut\Adapter\ReflectionSutAdapter;
use UniGen\Sut\SutInterface;
use UniGen\Sut\SutProviderInterface;
use UniGen\Sut\SutValidator;

class ReflectionSutProvider implements SutProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function provide(string $class): SutInterface
    {
        $sut = new ReflectionSutAdapter(new ReflectionClass($class));

        return $sut;
    }
}
