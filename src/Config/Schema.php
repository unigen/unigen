<?php
declare(strict_types=1);

namespace UniGen\Config;

use JsonSchema\Validator;
use UniGen\Config\Exception\InvalidConfigSchemaException;

class Schema
{
    public const LATEST_VERSION = 1;

    /** @var array[] */
    private $schema;

    /**
     * @param array $schema
     */
    public function __construct(array $schema)
    {
        $this->schema = $schema;
    }

    /**
     * @param array<string, mixed> $config
     *
     * @throws InvalidConfigSchemaException
     */
    public function validate(array $config): void
    {
        $configObject = (object) $config;
        $validator = new Validator();
        $validator->validate(
            $configObject,
            $this->schema
        );

        if (!$validator->isValid()) {
            $exception = new InvalidConfigSchemaException('Invalid config schema.');
            $exception->setViolations($validator->getErrors());

            throw $exception;
        }
    }
}
