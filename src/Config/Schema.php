<?php
declare(strict_types=1);

namespace UniGen\Config;

use JsonSchema\Validator;
use UniGen\Config\Exception\ConfigException;

class Schema
{
    public const LATEST_VERSION = 1;

    /** @var array[] */
    private $schema;

    /**
     * @param array<mixed, mixed> $schema
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
        $configObject = (object) $config;
        $validator = new Validator();
        $validator->validate(
            $configObject,
            $this->schema
        );

        if (!$validator->isValid()) {
            throw new ConfigException(
                sprintf(
                    'Invalid config schema. %s.',
                    $this->stringifyViolation(current($validator->getErrors()))
                )
            );
        }
    }

    /**
     * @param array<string, string> $violation
     *
     * @return string
     */
    private function stringifyViolation(array $violation): string
    {
        $property = $violation['property'];
        if (strlen($property) > 0) {
            $property = '"' . $property . '": ';
        }
        $violationMsg = trim(sprintf('%s %s', $property, $violation['message']));

        return rtrim($violationMsg, '.');
    }
}
