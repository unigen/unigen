<?php

declare(strict_types=1);

namespace UniGen\Config;

class Config
{
    /** @var array */
    private $parameters;

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    /**
     * @param array $parameters
     *
     * @return Config
     */
    public function merge(array $parameters): Config
    {
        $newThis = clone $this;
        $newThis->parameters = array_merge($this->parameters, array_filter($parameters));

        return $newThis;
    }

    /**
     * @return string
     */
    public function getTestPath(): string
    {
        return $this->parameters['testPath'];
    }

    /**
     * @return string
     */
    public function getTestNamespace(): string
    {
        return $this->parameters['testNamespace'];
    }

    /**
     * @return string
     */
    public function getCaseClass(): string
    {
        return $this->parameters['testCaseClass'];
    }

    /**
     * @return string
     */
    public function getMockFramework(): string
    {
        return $this->parameters['mockery'];
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->parameters['template'];
    }
}
