<?php
declare(strict_types=1);

namespace UniGen\Generator\Resolver;

use UniGen\Generator\Exception\PatternException;

class NamespaceResolver extends PatternBasedResolver
{
    private const NAMESPACE_SEPARATOR = '\\';

    /** @var string[] */
    private $patternParts;

    /**
     * @param string $pattern
     */
    public function __construct(string $pattern)
    {
        $this->patternParts =  explode(self::NAMESPACE_SEPARATOR, $pattern);
    }

    /**
     * @param string $namespace
     *
     * @return string
     *
     * @throws PatternException
     */
    public function resolve(string $namespace): string
    {
        $namespaces = explode(self::NAMESPACE_SEPARATOR, $namespace);

        $resolvedPatternParts = [];
        foreach ($this->patternParts as $patternPart) {
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
     * @throws PatternException
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
     * @throws PatternException
     */
    private function getPlaceholderReplacement(array $placeholder, array $namespaces): string
    {
        switch ($placeholder['type']) {
            case 'namespace':
                $index = $placeholder['index'];
                if ($index !== null) {
                    $namespaces = array_slice($namespaces, $index, $placeholder['offset']);
                }

                return implode(self::NAMESPACE_SEPARATOR, $namespaces);

            default:
                throw new PatternException(sprintf('Unknown namespace pattern "%s".', $placeholder['type']));
        }
    }
}