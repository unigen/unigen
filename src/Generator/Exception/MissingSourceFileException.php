<?php
declare(strict_types=1);

namespace UniGen\Generator\Exception;

class MissingSourceFileException extends GeneratorException
{
    /** @var string[] */
    private $missingFiles;

    /**
     * @return string[]
     */
    public function getMissingFiles(): array
    {
        return $this->missingFiles;
    }

    /**
     * @param string[] $missingFiles
     */
    public function setMissingFiles(array $missingFiles): void
    {
        $this->missingFiles = $missingFiles;
    }
}