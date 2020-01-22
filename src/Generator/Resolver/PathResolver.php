<?php
declare(strict_types=1);

namespace UniGen\Generator\Resolver;

use UniGen\Generator\Exception\GeneratorException;

class PathResolver extends PatternBasedResolver
{
    /**
     * @param string $pattern
     * @param string $path
     *
     * @return string
     *
     * @throws GeneratorException
     */
    public function resolve(string $pattern, string $path): string
    {
        $cwd = getcwd();
        if ($cwd === false) {
            throw new GeneratorException('Unable to determine current working directory.');
        }

        $relativePath = mb_substr(
            $path,
            mb_strlen($cwd) + 1
        );
        $pathInfo = pathinfo($relativePath);

        $replacements = [
            'dirnames' => explode(DIRECTORY_SEPARATOR, $pathInfo['dirname']),
            'extension' => $pathInfo['extension'] ?? '',
            'filename' => $pathInfo['filename']
        ];

        $resolvedPatternsParts = [];
        foreach (explode('/', $pattern) as $patternPart) {
            $resolvedPatternsParts[] = $this->resolvePatternPart($patternPart, $replacements);
        }

        return implode(DIRECTORY_SEPARATOR, $resolvedPatternsParts);
    }

    /**
     * @param string $patternPart
     * @param array<string, mixed> $replacements
     *
     * @return string
     *
     * @throws GeneratorException
     */
    private function resolvePatternPart(string $patternPart, array $replacements): string
    {
        if (!$this->patternHasPlaceholders($patternPart)) {
            return $patternPart;
        }

        foreach ($this->extractPlaceholders($patternPart) as $placeholder) {
            $replacement = $this->getPlaceholderReplacement($placeholder, $replacements);
            $patternPart = str_replace($placeholder[0], $replacement, $patternPart);
        }

        return $patternPart;
    }

    /**
     * @param array<string, mixed> $placeholder
     * @param array<string, mixed> $replacements
     *
     * @return string
     *
     * @throws GeneratorException
     */
    private function getPlaceholderReplacement(array $placeholder, array $replacements): string
    {
        $type = $placeholder['type'];
        switch ($type) {
            case 'extension':
            case 'filename':
                return $replacements[$type];

            case 'dirname':
                $dirnames = $replacements['dirnames'];
                $index = $placeholder['index'];
                if ($index !== null) {
                    $dirnames = array_slice($dirnames, (int) $index, $placeholder['index']);
                }

                return implode(DIRECTORY_SEPARATOR, $dirnames);

            default:
                throw new GeneratorException(sprintf('Unknown "testPath" pattern "%s".', $type));
        }
    }
}
