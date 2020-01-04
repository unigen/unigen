<?php
/**
 * This file is part of Boozt Platform
 * and belongs to Boozt Fashion AB.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

declare(strict_types = 1);

namespace UniGen\Config\Source;

use UniGen\Config\ConfigSource;
use UniGen\Config\Exception\ConfigSourceException;

interface SourceInterface
{
    /**
     * @param string $sourcePath
     *
     * @return ConfigSource
     *
     * @throws ConfigSourceException
     */
    public function fetch(string $sourcePath): ConfigSource;
}
