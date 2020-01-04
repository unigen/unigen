<?php
declare(strict_types=1);

namespace UniGen\Util;

use UniGen\Util\Exception\JsonFileLoaderException;

class JsonFileLoader
{
    /**
     * @param string $path
     *
     * @return array<string, mixed>
     *
     * @throws JsonFileLoaderException
     */
    public static function getContent(string $path): array
    {
        if (!file_exists($path)) {
            throw new JsonFileLoaderException(sprintf('File "%s" does not exist.', $path));
        }

        $content = file_get_contents($path);
        if ($path === false) {
            throw new JsonFileLoaderException(sprintf('Unable to fetch file content: "%s".', $path));
        }

        $content = json_decode($content, true);
        $lastError = json_last_error();
        if ($lastError !== JSON_ERROR_NONE) {
            throw new JsonFileLoaderException(
                sprintf('Invalid JSON file "%s". Error code #%d.', $path, $lastError)
            );
        }

        return $content;
    }
}