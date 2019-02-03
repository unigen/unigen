<?php

namespace UnitGen\Test\Sut\Provider;

use UnitGen\Sut\Adapter\ReflectionSutAdapter;
use UnitGen\Sut\Exception\SutValidatorException;
use UnitGen\Sut\SutInterface;
use UnitGen\Sut\SutValidator;
use Mockery;
use Mockery\MockInterface;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use UnitGen\Sut\Provider\ReflectionSutProvider;

class ReflectionSutProviderTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private const SUT_CLASS = 'stdClass';

    /** @var SutValidator|MockInterface */
    private $validator;

    /** @var SutInterface|MockInterface */
    private $sutMock;

    /** @var ReflectionSutProvider */
    private $sut;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->sutMock = Mockery::mock(SutInterface::class);
        $this->validator = Mockery::mock(SutValidator::class);

        $this->sut = new ReflectionSutProvider($this->validator);
    }

    public function testProvideShouldReturnSut()
    {
        $this->validator
            ->shouldReceive('validate')
            ->with(anInstanceOf(ReflectionSutAdapter::class));

        $this->assertInstanceOf(SutInterface::class, $this->sut->provide(self::SUT_CLASS));
        $this->assertInstanceOf(ReflectionSutAdapter::class, $this->sut->provide(self::SUT_CLASS));

        $this->sut->provide(self::SUT_CLASS);
    }

    public function testProvideShouldThrowExceptionWhenValidationFailed()
    {
        $this->validator
            ->shouldReceive('validate')
            ->with(anInstanceOf(ReflectionSutAdapter::class))
            ->andThrow(new SutValidatorException());

        $this->expectException(SutValidatorException::class);

        $this->sut->provide(self::SUT_CLASS);
    }
}
