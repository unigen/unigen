<?php
declare(strict_types=1);

namespace UniGen\Util\FileReader;

class PlainFileReader
{
    /**
     * @param string $path
     *
     * @return string
     *
     * @throws FileReaderException
     */
    public static function getContent(string $path): string
    {
        if (!file_exists($path)) {
            throw new FileReaderException(sprintf('File "%s" does not exist.', $path));
        }

        $content = file_get_contents($path);
        if ($path === false) {
            throw new FileReaderException(sprintf('Unable to fetch file content: "%s".', $path));
        }

        return $content;
    }
}