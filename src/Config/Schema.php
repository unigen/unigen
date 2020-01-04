<?php
declare(strict_types = 1);

namespace UniGen\Config;

use JsonSchema\Validator;
use UniGen\Config\Exception\ConfigException;

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
     * @throws ConfigException
     */
    public function validate(array $config): void
    {
        $validator = new Validator();
        $validator->validate(
            $config,
            $this->schema
        );

        if (!$validator->isValid()) {
            $exception = new ConfigException('Invalid config schema.');
            $exception->setViolations($validator->getErrors());

            throw $exception;
        }
    }
}
