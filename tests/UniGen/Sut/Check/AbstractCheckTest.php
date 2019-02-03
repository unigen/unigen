<?php

namespace UniGen\Test\Sut\Check;

use Mockery;
use Mockery\MockInterface;
use UniGen\Sut\SutInterface;
use PHPUnit\Framework\TestCase;
use UniGen\Sut\Check\AbstractCheck;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class AbstractCheckTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var SutInterface|MockInterface */
    private $sutMock;

    /** @var AbstractCheck */
    private $sut;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->sutMock = Mockery::mock(SutInterface::class);

        $this->sut = new AbstractCheck();
    }

    public function testMessageShouldReturnCorrectMessage()
    {
        $this->sutMock
            ->shouldReceive('getName')
            ->andReturn('sutName');

        $this->assertEquals('SUT cannot be an abstract class sutName', $this->sut->message($this->sutMock));
    }

    public function testAppliesToShouldReturnTrueIfSutIsAbstract()
    {
        $this->sutMock
            ->shouldReceive('isAbstract')
            ->andReturnTrue();

        $this->assertTrue($this->sut->appliesTo($this->sutMock));
    }

    public function testAppliesToShouldReturnFalseIfSutIsAbstract()
    {
        $this->sutMock
            ->shouldReceive('isAbstract')
            ->andReturnFalse();

        $this->assertFalse($this->sut->appliesTo($this->sutMock));
    }
}
