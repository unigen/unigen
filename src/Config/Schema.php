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

use UniGen\Config\Exception\SchemaException;

/** Class JsonSchema */
class Schema
{
    /** @var array[] */
    private $schema;

    /**
     * @param array $schema
     */
    public function __construct(array $schema)
    {
        $this->schema = $schema;
    }


    public function validate(array $rawConfig): void
    {
        
    }

    /**
     * @param string $schemaPath
     *
     * @return static
     * @throws SchemaException
     */
    public static function createFromFile(string $schemaPath): self
    {
        if (!file_exists($schemaPath)) {
            throw new SchemaException('Schema file "%s" does not exist.', $schemaPath);
        }

        $content = file_get_contents($schemaPath);
        if ($content === false) {
            throw new SchemaException(sprintf('Unable to fetch schema content: "%s".', $schemaPath));
        }

        $content = json_decode($content, true);
        $lastError = json_last_error();
        if ($lastError !== JSON_ERROR_NONE) {
            throw new SchemaException(sprintf('Invalid schema file "%s". Error code #d.', $lastError));
        }

        return new self($content);
    }
}
