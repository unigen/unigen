<?php
declare(strict_types=1);

namespace UniGen\Generator\Resolver;

class PathResolver
{
    /** @var string[] */
    private $patternParts;

    /**
     * @param string $pattern
     */
    public function __construct(string $pattern)
    {
        //    'tests/unit/<dirname(1,)>/<filename>Test.<extension>',
        $this->patternParts = explode('/', $pattern);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function resolve(string $path): string
    {
        $relativePath = substr(
            $path,
            strlen(getcwd()) + 1
        );
        $pathInfo = pathinfo($relativePath);

        $replacements = [
            'dirnames' => explode(DIRECTORY_SEPARATOR, $pathInfo['dirname']),
            'extension' => $pathInfo['extension'],
            'filename' => $pathInfo['filename']
        ];

        $newPathParts = [];
        foreach ($this->patternParts as $patternPart) {
            $newPathParts[] = $this->resolvePart($patternPart, $replacements);
        }

        return implode(DIRECTORY_SEPARATOR, $newPathParts);
    }

    private function resolvePart(string $patternPart, array $replacements)
    {
        if (!$this->patternHasPlaceholders($patternPart)) {
            return $patternPart;
        }

        $matches = [];
        preg_match_all('/<(?:(?<type>[^(>]+)(?:\((?<index>[0-9]+)(?:,(?<offset>[0-9]+))?\))?)>/', $patternPart, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            switch ($match['type']) {
                case 'extension':
                    $replacement = $replacements['extension'];
                    break;

                case 'filename':
                    $replacement = $replacements['filename'];
                    break;

                case 'dirname':
                    $dirnames = $replacements['dirnames'];
                    $index = $match['index'] ?? null;
                    $offset = $match['offset'] ?? null;
                    if ($offset !== null) {
                        $offset = (int) $offset;
                    }

                    if ($index !== null) {
                        $dirnames = array_slice($dirnames, (int) $index, $offset);
                    }

                    $replacement = implode(DIRECTORY_SEPARATOR, $dirnames);
                    break;

                    // TODO
                default:
                    $replacement = 'xxx';
            }

            $patternPart = str_replace($match[0], $replacement, $patternPart);
        }

        // todo sprawdz czy nie ma brakujacych

        return $patternPart;
    }

    private function patternHasPlaceholders(string $patternPart) : bool
    {
        $result = preg_match('/<(?:[^>]+)>/', $patternPart);

        return $result === 1
            ? true
            : false;
    }
}