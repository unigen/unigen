<?php
declare(strict_types=1);

namespace UniGen\Util\FileLoader;

class JsonFileLoader
{
    /**
     * @param string $path
     *
     * @return array<string, mixed>
     *
     * @throws FileLoaderException
     */
    public static function getContent(string $path): array
    {
        $content = PlainFileLoader::getContent($path);

        $content = json_decode($content, true);
        $lastError = json_last_error();
        if ($lastError !== JSON_ERROR_NONE) {
            throw new FileLoaderException(
                sprintf('Invalid JSON file "%s". Error code #%d.', $path, $lastError)
            );
        }

        return $content;
    }
}