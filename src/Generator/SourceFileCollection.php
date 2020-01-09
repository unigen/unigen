<?php
declare(strict_types = 1);

namespace UniGen\Generator;

class SourceFileCollection
{
    /** @var string[] */
    private $existingSourceFiles = [];

    /** @var string[] */
    private $missingSourceFiles = [];

    /**
     * @param string[] $sourceFiles
     */
    public function __construct(array $sourceFiles)
    {
        $this->resolveSourceFiles($sourceFiles);
    }

    /**
     * @param string[] $sourceFiles
     */
    private function resolveSourceFiles(array $sourceFiles): void
    {
        foreach (array_unique($sourceFiles) as $sourceFile) {
            $resolvedSourceFile = realpath($sourceFile);
            if ($resolvedSourceFile === false) {
                $this->missingSourceFiles[] = $sourceFile;
            } else {
                $this->existingSourceFiles[] = $resolvedSourceFile;
            }
        }
    }

    /**
     * @return bool
     */
    public function hasSome(): bool
    {
        return $this->hasExisting() || $this->hasMissing();
    }

    /**
     * @return bool
     */
    public function hasExisting(): bool
    {
        return count($this->existingSourceFiles) > 0;
    }

    /**
     * @return bool
     */
    public function hasMissing(): bool
    {
        return count($this->missingSourceFiles) > 0;
    }

    /**
     * @return string[]
     */
    public function getExisting(): array
    {
        return $this->existingSourceFiles;
    }

    /**
     * @return string[]
     */
    public function getMissing(): array
    {
        return $this->missingSourceFiles;
    }
}
