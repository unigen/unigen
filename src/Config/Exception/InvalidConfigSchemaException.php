<?php
declare(strict_types=1);

namespace UniGen\Config\Exception;

class InvalidConfigSchemaException extends ConfigException
{
    /** @var string[] */
    private $violations = [];

    /**
     * @return string[]
     */
    public function getViolations(): array
    {
        return $this->violations;
    }

    /**
     * @param string[] $violations
     */
    public function setViolations(array $violations): void
    {
        $this->violations = $violations;
    }
}