<?php
declare(strict_types=1);

namespace UniGen\Sut;

use ReflectionClass;
use UniGen\Sut\Reflection\ReflectionSutAdapter;

class SutFactory
{
    /**
     * @param string $class
     *
     * @return SutInterface
     */
    public function create(string $class): SutInterface
    {
        // TODO wrap exception
        return new ReflectionSutAdapter(new ReflectionClass($class));
    }
}