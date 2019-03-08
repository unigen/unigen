<?php

declare(strict_types=1);

namespace UniGen\Sut\Adapter;

use ReflectionParameter;
use UniGen\Util\ScalarValueResolver;
use UniGen\Sut\SutDependencyInterface;

class ReflectionSutDependencyAdapter implements SutDependencyInterface
{
    const UNKNOWN_TYPE = 'mixed';

    /** @var ReflectionParameter */
    private $propertyReflection;

    /**
     * @param ReflectionParameter $propertyReflection
     */
    public function __construct(ReflectionParameter $propertyReflection)
    {
        $this->propertyReflection = $propertyReflection;
    }

    /**
     * {@inheritdoc}
     */
    public function isObject()
    {
        return (bool) $this->propertyReflection->getClass();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->propertyReflection->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        if ($this->propertyReflection->isDefaultValueAvailable()) {
            return $this->propertyReflection->getDefaultValue();
        }

        return ScalarValueResolver::resolve($this->getType());
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        if ($this->propertyReflection->hasType()) {
            return (string) $this->propertyReflection->getType();
        }

        return self::UNKNOWN_TYPE;
    }

    /**
     * {@inheritdoc}
     */
    public function getShortName()
    {
        if ($this->isObject()) {
            return $this->propertyReflection->getClass()->getShortName();
        }

        return $this->getType();
    }
}
