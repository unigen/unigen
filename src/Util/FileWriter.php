<?php
declare(strict_types=1);

namespace UniGen\Util;

use UniGen\Util\Exception\FileWriterException;

class FileWriter
{
    /**
     * @param string $path
     * @param string $content
     * @param bool $override
     *
     * @throws FileWriterException
     */
    public function write(string $path, string $content, bool $override): void
    {
        if (!$override && file_exists($path)) {
            throw new FileWriterException(sprintf('File "%s" already exists.', $path));
        }

        $dir = dirname($path);
        if (!is_dir($dir)) {
            $dirCreated = mkdir($dir, 0777, true);
            if (!$dirCreated) {
                throw new FileWriterException(sprintf('Can\'t create directory "%d".', $dir));
            }
        }

        $fileCreated = file_put_contents($path, $content);
        if (!$fileCreated) {
            throw new FileWriterException(sprintf('Unable to save file "%s".', $path));
        }
    }

}