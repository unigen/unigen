<?php

declare(strict_types=1);

namespace UnitGen;

class Config
{
    /** @var string */
    private $parentTestCase;

    /** @var string */
    private $mockObjectFramework;

    /** @var string */
    private $testTargetPathPattern;

    /** @var string */
    private $testTargetPathReplacementPattern;

    /** @var string */
    private $namespacePattern;

    /** @var string */
    private $namespaceReplacementPattern;

    /**
     * @param string $parentTestCase
     * @param string $mockObjectFramework
     * @param string $testTargetPathPattern
     * @param string $testTargetPathReplacementPattern
     * @param string $namespacePattern
     * @param string $namespaceReplacementPattern
     */
    public function __construct(
        string $parentTestCase,
        string $mockObjectFramework,
        string $testTargetPathPattern,
        string $testTargetPathReplacementPattern,
        string $namespacePattern,
        string $namespaceReplacementPattern
    ) {
        $this->parentTestCase = $parentTestCase;
        $this->mockObjectFramework = $mockObjectFramework;
        $this->testTargetPathPattern = $testTargetPathPattern;
        $this->testTargetPathReplacementPattern = $testTargetPathReplacementPattern;
        $this->namespacePattern = $namespacePattern;
        $this->namespaceReplacementPattern = $namespaceReplacementPattern;
    }

    /**
     * @return string
     */
    public function parentTestCase(): string
    {
        return $this->parentTestCase;
    }

    /**
     * @return string
     */
    public function mockObjectFramework(): string
    {
        return $this->mockObjectFramework;
    }

    /**
     * @return string
     */
    public function targetPathPattern(): string
    {
        return $this->testTargetPathPattern;
    }

    /**
     * @return string
     */
    public function targetPathReplacementPattern(): string
    {
        return $this->testTargetPathReplacementPattern;
    }

    /**
     * @return string
     */
    public function namespacePattern(): string
    {
        return $this->namespacePattern;
    }

    /**
     * @return string
     */
    public function namespaceReplacePattern(): string
    {
        return $this->namespaceReplacementPattern;
    }
}
