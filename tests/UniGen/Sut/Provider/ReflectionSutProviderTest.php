<?php

namespace UniGen\Test\Sut\Provider;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use UniGen\Sut\Adapter\ReflectionSutAdapter;
use UniGen\Sut\Exception\GeneratorException;
use UniGen\Sut\Provider\ReflectionSutProvider;
use UniGen\Sut\SutInterface;
use UniGen\Sut\SutValidator;

class ReflectionSutProviderTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    const SUT_CLASS = 'stdClass';

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
            ->andThrow(new GeneratorException());

        $this->expectException(GeneratorException::class);

        $this->sut->provide(self::SUT_CLASS);
    }
}
