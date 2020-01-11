<?php
declare(strict_types=1);

namespace UniGen\Sut;

use ReflectionClass;
use ReflectionException;
use UniGen\Sut\Reflection\ReflectionSutAdapter;

class SutFactory
{
    /**
     * @param string $class
     *
     * @return SutInterface
     *
     * @throws SutException
     */
    public function create(string $class): SutInterface
    {
        try {
            return new ReflectionSutAdapter(new ReflectionClass($class));
        } catch (ReflectionException $exception) {
            throw new SutException(
                sprintf('Class "%s" does not exist.', $class),
                0,
                $exception
            );
        }
    }
}
