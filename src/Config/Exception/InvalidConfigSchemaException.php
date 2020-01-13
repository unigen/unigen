<?php
declare(strict_types=1);

namespace UniGen\Config\Exception;

class InvalidConfigSchemaException extends ConfigException
{
    /** @var array[] */
    private $violations = [];

    /**
     * @return array[]
     */
    public function getViolations(): array
    {
        return $this->violations;
    }

    /**
     * @param array[] $violations
     */
    public function setViolations(array $violations): void
    {
        $this->violations = $violations;
    }
}
