<?php

namespace UniGen\Test\Sut\Check;

use Mockery;
use Mockery\MockInterface;
use UniGen\Sut\SutInterface;
use PHPUnit\Framework\TestCase;
use UniGen\Sut\Check\InterfaceCheck;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class InterfaceCheckTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var SutInterface|MockInterface */
    private $sutMock;

    /** @var InterfaceCheck */
    private $sut;

    /**
     * {@inheritdoc}
    */
    public function setUp()
    {
        $this->sutMock = Mockery::mock(SutInterface::class);

        $this->sut = new InterfaceCheck();
    }

    public function testMessageShouldReturnCorrectMessage()
    {
        $this->sutMock
            ->shouldReceive('getName')
            ->andReturn('sutName');

        $this->assertEquals('SUT cannot be an interface sutName', $this->sut->message($this->sutMock));
    }

    public function testAppliesToShouldReturnTrueIfSutIsInterface()
    {
        $this->sutMock
            ->shouldReceive('isInterface')
            ->andReturnTrue();

        $this->assertTrue($this->sut->appliesTo($this->sutMock));
    }

    public function testAppliesToShouldReturnFalseIfSutIsInterface()
    {
        $this->sutMock
            ->shouldReceive('isInterface')
            ->andReturnFalse();

        $this->assertFalse($this->sut->appliesTo($this->sutMock));
    }
}
