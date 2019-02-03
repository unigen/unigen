<?php

namespace UniGen\Test;

use Mockery\MockInterface;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use UniGen\Config;

class ConfigTest extends TestCase
{
    const MOCK_FRAMEWORK = 'framework';
    const PARENT_TEST_CLASS = 'parent';
    const TEST_PATH_PATTERN = 'pathPattern';
    const NAMESPACE_PATTERN = 'namespacePattern';
    const TEST_PATH_REPLACEMENT_PATTERN = 'pathReplacementPattern';
    const NAMESPACE_REPLACEMENT_PATTERN = 'namespaceReplacementPattern';

    use MockeryPHPUnitIntegration;

    /** @var string|MockInterface */
    private $parentTestCase;

    /** @var string|MockInterface */
    private $mockObjectFramework;

    /** @var string|MockInterface */
    private $testTargetPathPattern;

    /** @var string|MockInterface */
    private $testTargetPathReplacementPattern;

    /** @var string|MockInterface */
    private $namespacePattern;

    /** @var string|MockInterface */
    private $namespaceReplacementPattern;

    /** @var Config */
    private $sut;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->parentTestCase = self::PARENT_TEST_CLASS;
        $this->mockObjectFramework = self::MOCK_FRAMEWORK;
        $this->namespacePattern = self::NAMESPACE_PATTERN;
        $this->testTargetPathPattern = self::TEST_PATH_PATTERN;
        $this->namespaceReplacementPattern = self::NAMESPACE_REPLACEMENT_PATTERN;
        $this->testTargetPathReplacementPattern = self::TEST_PATH_REPLACEMENT_PATTERN;

        $this->sut = new Config(
            $this->parentTestCase,
            $this->mockObjectFramework,
            $this->testTargetPathPattern,
            $this->testTargetPathReplacementPattern,
            $this->namespacePattern,
            $this->namespaceReplacementPattern
        );
    }

    public function testParentTestCase()
    {
        $this->assertEquals($this->sut->parentTestCase(), self::PARENT_TEST_CLASS);
    }

    public function testMockObjectFramework()
    {
        $this->assertEquals($this->sut->mockObjectFramework(), self::MOCK_FRAMEWORK);
    }

    public function testTargetPathPattern()
    {
        $this->assertEquals($this->sut->targetPathPattern(), self::TEST_PATH_PATTERN);
    }

    public function testNamespacePattern()
    {
        $this->assertEquals($this->sut->namespacePattern(), self::NAMESPACE_PATTERN);
    }

    public function testNamespaceReplacePattern()
    {
        $this->assertEquals($this->sut->namespaceReplacePattern(), self::NAMESPACE_REPLACEMENT_PATTERN);
    }

    public function testTargetPathReplacementPattern()
    {
        $this->assertEquals($this->sut->targetPathReplacementPattern(), self::TEST_PATH_REPLACEMENT_PATTERN);
    }
}
