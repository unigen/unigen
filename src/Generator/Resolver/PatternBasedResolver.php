<?php
declare(strict_types=1);

namespace UniGen\Generator\Resolver;

abstract class PatternBasedResolver
{
    /**
     * @param string $patternPart
     *
     * @return bool
     */
    protected function patternHasPlaceholders(string $patternPart) : bool
    {
        $result = preg_match('/<(?:[^>]+)>/', $patternPart);

        return $result === 1
            ? true
            : false;
    }

    /**
     * @param string $patternPart
     *
     * @return array[]
     */
    protected function extractPlaceholders(string $patternPart): array
    {
        $matches = [];
        preg_match_all(
            '/<(?:(?<type>[^(>]+)(?:\((?<index>[0-9]+)(?:,(?<offset>[0-9]+))?\))?)>/',
            $patternPart,
            $matches,
            PREG_SET_ORDER
        );

        $placeholders = [];
        foreach ($matches as $match) {
            foreach (['index', 'offset'] as $key) {

                $match[$key] = array_key_exists($key, $match)
                    ? (int) $match[$key]
                    : null;
            }
            $placeholders[] = $match;
        }

        return $placeholders;
    }
}
