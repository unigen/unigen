<?php

namespace UnitGen\Sut;

interface SutInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * @return bool
     */
    public function isTrait(): bool;

    /**
     * @return bool
     */
    public function isAbstract(): bool;

    /**
     * @return bool
     */
    public function isInterface(): bool;

    /**
     * @return string
     */
    public function getShortName(): string;

    /**
     * @return bool
     */
    public function hasNamespace(): bool;

    /**
     * @return string
     */
    public function getNamespace(): string;

    /**
     * @return bool
     */
    public function hasDependencies(): bool;

    /**
     * @return SutDependencyInterface[]
     */
    public function getDependencies(): array;

    /**
     * @return string[]
     */
    public function getPublicMethods(): array;
}
