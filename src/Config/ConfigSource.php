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

namespace UniGen\Config;

class ConfigSource
{
    /** @var array<string, mixed> */
    private $content;

    /**
     * @param array<string, mixed> $content
     */
    public function __construct(array $content)
    {
        $this->content = $content;
    }

    /**
     * @return array<string, mixed>
     */
    public function getContent(): array
    {
        return $this->content;
    }
}
