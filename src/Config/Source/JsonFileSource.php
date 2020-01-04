<?php

declare(strict_types=1);

namespace UniGen\Config\Source;

use UniGen\Config\ConfigSource;
use UniGen\Config\Exception\ConfigSourceException;

class JsonFileSource implements SourceInterface
{
    /**
     * {@inheritDoc}
     */
    public function fetch(string $configPath): ConfigSource
    {
        if (!file_exists($configPath)) {
            throw new ConfigSourceException(sprintf('Config file "%s" does not exist.', $configPath));
        }

        $content = file_get_contents($configPath);
        if ($configPath === false) {
            throw new ConfigSourceException(sprintf('Unable to fetch file content: "%s".', $configPath));
        }

        $content = json_decode($content, true);
        $lastError = json_last_error();
        if ($lastError !== JSON_ERROR_NONE) {
            throw new ConfigSourceException(sprintf('Invalid JSON config file "%s". Error code #d.', $lastError));
        }

        return new ConfigSource($content);
    }
}
