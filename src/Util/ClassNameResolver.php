<?php

declare(strict_types=1);

namespace UniGen\Util;

class ClassNameResolver
{
    const NAMESPACE_SEPARATOR = '\\';
    const CLASS_PATTERN = '/class\s(\w+)/';
    const NAMESPACE_PATTERN = '/namespace\s(.+);/';

    /**
     * @param string $content
     *
     * @return string
     */
    public static function resolve(string $content): string
    {
        return self::extractNamespace($content) . self::NAMESPACE_SEPARATOR . self::extractClass($content);
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private static function extractClass(string $content): string
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
    private static function extractNamespace(string $content): string
    {
        return self::extract(self::NAMESPACE_PATTERN, $content);
    }

    /**
     * @param string $pattern
     * @param string $content
     *
     * @return string
     */
    private static function extract(string $pattern, string $content): string
    {
        preg_match($pattern, $content, $matches);

        if (empty($matches[1])) {
            return '';
        }

        return $matches[1];
    }
}
