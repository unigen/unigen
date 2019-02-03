<?php

namespace UniGen\Test\Sut;

use Mockery;
use Mockery\MockInterface;
use UniGen\Sut\SutInterface;
use UniGen\Sut\SutValidator;
use PHPUnit\Framework\TestCase;
use UniGen\Sut\SutCheckInterface;
use UniGen\Sut\Exception\SutValidatorException;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class SutValidatorTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var SutInterface|MockInterface */
    private $sutMock;

    /** @var SutValidator */
    private $sut;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->sutMock = Mockery::mock(SutInterface::class);

        $this->sut = new SutValidator();
    }

    public function testValidateShouldNotThrowExceptionWhenThereIsNoChecks()
    {
        $this->sut->validate($this->sutMock);
    }

    public function testValidateShouldThrowExceptionOnFirstFailedCheck()
    {
        $validCheck = Mockery::mock(SutCheckInterface::class);

        $validCheck
            ->shouldReceive('appliesTo')
            ->once()
            ->andReturnFalse();

        $invalidCheck = Mockery::mock(SutCheckInterface::class);

        $invalidCheck
            ->shouldReceive('appliesTo')
            ->once()
            ->andReturnTrue();

        $invalidCheck
            ->shouldReceive('message')
            ->andReturn('Second check');

        $this->expectException(SutValidatorException::class);
        $this->expectExceptionMessage('Second check');

        $this->sut->addCheck($validCheck);
        $this->sut->addCheck($invalidCheck);

        $this->sut->validate($this->sutMock);
    }

    public function testValidateShouldNotCheckOthersChecksWhenAfterFail()
    {
        $validCheck = Mockery::mock(SutCheckInterface::class);

        $validCheck
            ->shouldReceive('appliesTo')
            ->never()
            ->andReturnFalse();

        $invalidCheck = Mockery::mock(SutCheckInterface::class);

        $invalidCheck
            ->shouldReceive('appliesTo')
            ->once()
            ->andReturnTrue();

        $invalidCheck
            ->shouldReceive('message')
            ->andReturn('Second check');

        $this->expectException(SutValidatorException::class);
        $this->expectExceptionMessage('Second check');

        $this->sut->addCheck($invalidCheck);
        $this->sut->addCheck($validCheck);

        $this->sut->validate($this->sutMock);
    }
}
