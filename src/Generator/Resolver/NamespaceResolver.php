<?php
declare(strict_types=1);

namespace UniGen\Generator\Resolver;

use UniGen\Generator\Exception\GeneratorException;

class NamespaceResolver extends PatternBasedResolver
{
    private const NAMESPACE_SEPARATOR = '\\';

    /**
     * @param string $pattern
     * @param string $namespace
     *
     * @return string
     *
     * @throws GeneratorException
     */
    public function resolve(string $pattern, string $namespace): string
    {
        $namespaces = explode(self::NAMESPACE_SEPARATOR, $namespace);

        $resolvedPatternParts = [];
        foreach (explode(self::NAMESPACE_SEPARATOR, $pattern) as $patternPart) {
            $resolvedPatternParts[] = $this->resolvePatternPart($patternPart, $namespaces);
        }

        return implode(self::NAMESPACE_SEPARATOR, $resolvedPatternParts);
    }

    /**
     * @param string $patternPart
     * @param string[] $namespaces
     *
     * @return string
     *
     * @throws GeneratorException
     */
    private function resolvePatternPart(string $patternPart, array $namespaces): string
    {
        if (!$this->patternHasPlaceholders($patternPart)) {
            return $patternPart;
        }

        foreach ($this->extractPlaceholders($patternPart) as $placeholder) {
            $replacement = $this->getPlaceholderReplacement($placeholder, $namespaces);
            $patternPart = str_replace($placeholder[0], $replacement, $patternPart);
        }

        return $patternPart;
    }

    /**
     * @param array<string, mixed> $placeholder
     * @param string[] $namespaces
     *
     * @return string
     *
     * @throws GeneratorException
     */
    private function getPlaceholderReplacement(array $placeholder, array $namespaces): string
    {
        $type = $placeholder['type'];
        switch ($type) {
            case 'namespace':
                $index = $placeholder['index'];
                if ($index !== null) {
                    $namespaces = array_slice($namespaces, $index, $placeholder['offset']);
                }

                return implode(self::NAMESPACE_SEPARATOR, $namespaces);

            default:
                throw new GeneratorException(sprintf('Unknown "testNamespace" pattern "%s".', $type));
        }
    }
}
