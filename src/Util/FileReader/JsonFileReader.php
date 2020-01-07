<?php
declare(strict_types=1);

namespace UniGen\Util\FileReader;

class JsonFileReader
{
    /**
     * @param string $path
     *
     * @return array<string, mixed>
     *
     * @throws FileReaderException
     */
    public static function getContent(string $path): array
    {
        $content = PlainFileReader::getContent($path);

        $content = json_decode($content, true);
        $lastError = json_last_error();
        if ($lastError !== JSON_ERROR_NONE) {
            throw new FileReaderException(
                sprintf('Invalid JSON file "%s". Error code #%d.', $path, $lastError)
            );
        }

        return $content;
    }
}