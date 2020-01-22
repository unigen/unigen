<?php
declare(strict_types=1);

namespace UniGen\Sut;

interface SutDependencyInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return bool
     */
    public function isObject();

    /**
     * @return string
     */
    public function getShortName();
}
