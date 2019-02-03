<?php

namespace UniGen\Test\Sut\Check;

use Mockery;
use Mockery\MockInterface;
use UniGen\Sut\SutInterface;
use PHPUnit\Framework\TestCase;
use UniGen\Sut\Check\TraitCheck;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class TraitCheckTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var SutInterface|MockInterface */
    private $sutMock;

    /** @var TraitCheck */
    private $sut;

    /**
     * {@inheritdoc}
    */
    public function setUp()
    {
        $this->sutMock = Mockery::mock(SutInterface::class);

        $this->sut = new TraitCheck();
    }


    public function testMessageShouldReturnCorrectMessage()
    {
        $this->sutMock
            ->shouldReceive('getName')
            ->andReturn('sutName');

        $this->assertEquals('SUT cannot be a trait sutName', $this->sut->message($this->sutMock));
    }

    public function testAppliesToShouldReturnTrueIfSutIsTrait()
    {
        $this->sutMock
            ->shouldReceive('isTrait')
            ->andReturnTrue();

        $this->assertTrue($this->sut->appliesTo($this->sutMock));
    }

    public function testAppliesToShouldReturnFalseIfSutIsTrait()
    {
        $this->sutMock
            ->shouldReceive('isTrait')
            ->andReturnFalse();

        $this->assertFalse($this->sut->appliesTo($this->sutMock));
    }
}
