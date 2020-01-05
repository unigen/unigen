<?php
declare(strict_types=1);

namespace UniGen\Util\FileLoader;

class PlainFileLoader
{
    /**
     * @param string $path
     *
     * @return string
     *
     * @throws FileLoaderException
     */
    public static function getContent(string $path): string
    {
        if (!file_exists($path)) {
            throw new FileLoaderException(sprintf('File "%s" does not exist.', $path));
        }

        $content = file_get_contents($path);
        if ($path === false) {
            throw new FileLoaderException(sprintf('Unable to fetch file content: "%s".', $path));
        }

        return $content;
    }
}