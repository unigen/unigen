<?php

declare(strict_types=1);

namespace UnitGen\FileSystem;

use UnitGen\FileSystem\Exception\FileSystemException;

class NativeFileSystem implements FileSystemInterface
{
    private const DEFAULT_FILE_PERMISSION = 0755;

    /**
     * {@inheritdoc}
     */
    public function exist(string $path): bool
    {
        return file_exists($path);
    }

    /**
     * {@inheritdoc}
     */
    public function read(string $path): string
    {
        if (!$content = file_get_contents($path)) {
            throw new FileSystemException("Error occurred during file read process");
        }

        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function write(string $path, string $content)
    {
        $directory = dirname($path);

        if (!is_dir($directory)) {
            mkdir($directory, self::DEFAULT_FILE_PERMISSION, true);
        }

        file_put_contents($path, $content);
    }
}
