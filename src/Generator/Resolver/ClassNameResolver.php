<?php

declare(strict_types=1);

namespace UniGen\Generator\Resolver;

// TODO refactor
// https://stackoverflow.com/questions/7153000/get-class-name-from-file
class ClassNameResolver
{
    private const NAMESPACE_SEPARATOR = '\\';
    private const CLASS_PATTERN = '/class\s(\w+)/';
    private const NAMESPACE_PATTERN = '/namespace\s(.+);/';

    /**
     * @param string $path
     *
     * @return string
     */
    public function resolveFromFile(string $path): string
    {
        // TODO class loader
        $content = file_get_contents($path);

        return $this->resolve($content);
    }

    /**
     * @param string $content
     *
     * @return string
     */
    public function resolve(string $content): string
    {
        return self::extractNamespace($content) . self::NAMESPACE_SEPARATOR . self::extractClass($content);
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function extractClass(string $content): string
    {
        $class = self::extract(self::CLASS_PATTERN, $content);

        if (empty($class)) {
            throw new \InvalidArgumentException("Given file content must contains class syntax");
        }

        return $class;
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function extractNamespace(string $content): string
    {
        return self::extract(self::NAMESPACE_PATTERN, $content);
    }

    /**
     * @param string $pattern
     * @param string $content
     *
     * @return string
     */
    private function extract(string $pattern, string $content): string
    {
        preg_match($pattern, $content, $matches);

        if (empty($matches[1])) {
            return '';
        }

        return $matches[1];
    }
}
