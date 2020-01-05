<?php
declare(strict_types=1);

namespace UniGen\Generator\Resolver;

class NamespaceResolver
{
    /** @var string */
    private $pattern;

    /**
     * @param string $pattern
     */
    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @param string $namespace
     *
     * @return string
     */
    public function resolve(string $namespace): string
    {
        return '';
    }
}