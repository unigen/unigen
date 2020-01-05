<?php
declare(strict_types=1);

namespace UniGen\Generator\Resolver;

class PathResolver
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
     * @param string $path
     *
     * @return string
     */
    public function resolve(string $path): string
    {
        return '';
    }
}