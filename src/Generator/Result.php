<?php


namespace UniGen\Generator;


class Result
{
    /** @var string */
    private $testPath;

    /**
     * Result constructor.
     * @param string $testPath
     */
    public function __construct(string $testPath)
    {
        $this->testPath = $testPath;
    }

    public function getTestPath(): string
    {
        return $this->testPath;
    }
}