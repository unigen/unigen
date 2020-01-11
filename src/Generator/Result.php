<?php
declare(strict_types=1);

namespace UniGen\Generator;

class Result
{
    /** @var string */
    private $testPath;

    /**
     * @param string $testPath
     */
    public function __construct(string $testPath)
    {
        $this->testPath = $testPath;
    }

    /**
     * @return string
     */
    public function getTestPath(): string
    {
        return $this->testPath;
    }
}
