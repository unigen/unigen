<?php
declare(strict_types=1);

namespace UniGen\Sut\Reflection;

use ReflectionClass;
use ReflectionMethod;
use UniGen\Sut\SutInterface;

class ReflectionSutAdapter implements SutInterface
{
    /** @var ReflectionClass */
    private $reflection;

    /**
     * @param ReflectionClass $reflection
     */
    public function __construct(ReflectionClass $reflection)
    {
        $this->reflection = $reflection;
    }

    /**
     * {@inheritdoc}
     */
    public function isInterface(): bool
    {
        return $this->reflection->isInterface();
    }

    /**
     * {@inheritdoc}
     */
    public function isTrait(): bool
    {
        return $this->reflection->isTrait();
    }

    /**
     * {@inheritdoc}
     */
    public function isAbstract(): bool
    {
        return $this->reflection->isAbstract();
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->reflection->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getShortName(): string
    {
        return $this->reflection->getShortName();
    }

    /**
     * {@inheritdoc}
     */
    public function hasNamespace(): bool
    {
        return !empty($this->reflection->getNamespaceName());
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace(): string
    {
        return $this->reflection->getNamespaceName();
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        /** @var string $filename */
        $filename = $this->reflection->getFileName();

        return  $filename;
    }

    /**
     * {@inheritdoc}
     */
    public function hasDependencies(): bool
    {
        if (!$this->reflection->getConstructor()) {
            return false;
        }

        return (bool)$this->reflection->getConstructor()->getParameters();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        if (!$this->reflection->getConstructor()) {
            return [];
        }

        $dependencies = [];

        foreach ($this->reflection->getConstructor()->getParameters() as $parameter) {
            $dependencies[] = new ReflectionSutDependencyAdapter($parameter);
        }

        return $dependencies;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublicMethods(): array
    {
        $methods = [];

        foreach ($this->reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if (!$method->isConstructor() && $method->getDeclaringClass()->getName() === $this->reflection->getName()) {
                $methods[] = $method->getName();
            }
        }

        return $methods;
    }
}
