<?php

declare(strict_types=1);

namespace UniGen\Config;

class Config
{
    /** @var array[string][mixed] */
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
     * @return void
     */
    public function merge(array $parameters): void
    {
        $this->parameters = array_merge($this->parameters, array_filter($parameters));
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->parameters[$key] = $value;
    }

    /**
     * @param string $key
     * @param null   $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->has($key) ? $this->parameters[$key] : $default;
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function has($key): bool
    {
        return isset($this->parameters[$key]);
    }
}
