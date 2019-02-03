<?php

namespace UnitGen\FileSystem;

use UnitGen\FileSystem\Exception\FileSystemException;

interface FileSystemInterface
{
    /**
     * @param string $path
     *
     * @throws FileSystemException
     *
     * @return bool
     */
    public function exist(string $path): bool;

    /**
     * @param string $path
     *
     * @throws FileSystemException
     *
     * @return string
     */
    public function read(string $path): string;

    /**
     * @param string $path
     * @param string $content
     *
     * @throws FileSystemException
     */
    public function write(string $path, string $content);
}
