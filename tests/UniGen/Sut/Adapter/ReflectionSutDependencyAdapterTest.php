<?php

namespace UniGen\Test\Sut\Adapter;

use ReflectionParameter;
use Mockery;
use Mockery\MockInterface;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use UniGen\Sut\Adapter\ReflectionSutDependencyAdapter;

class ReflectionSutDependencyAdapterTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var ReflectionParameter|MockInterface */
    private $propertyReflection;

    /** @var ReflectionSutDependencyAdapter */
    private $sut;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->propertyReflection = Mockery::mock(ReflectionParameter::class);

        $this->sut = new ReflectionSutDependencyAdapter(
            $this->propertyReflection
        );
    }

    public function testIsObjectShouldReturnTrue()
    {
        $class = Mockery::mock(\ReflectionClass::class);

        $this->propertyReflection
            ->shouldReceive('getClass')
            ->andReturn($class);

        $this->assertTrue($this->sut->isObject());
    }

    public function testIsObjectShouldReturnFalse()
    {
        $this->propertyReflection
            ->shouldReceive('getClass')
            ->andReturnNull();

        $this->assertFalse($this->sut->isObject());
    }

    public function testGetNameShouldReturnCorrectName()
    {
        $this->propertyReflection
            ->shouldReceive('getName')
            ->andReturn('dependencyName');

        $this->assertEquals('dependencyName', $this->sut->getName());
    }

    public function testGetValueShouldReturnDefaultType()
    {
        $this->propertyReflection
            ->shouldReceive('isDefaultValueAvailable')
            ->andReturnTrue();

        $this->propertyReflection
            ->shouldReceive('getDefaultValue')
            ->once()
            ->andReturn('defaultValue');

        $this->assertEquals('defaultValue', $this->sut->getValue());
    }

    public function testGetValueShouldResolveIfDefaultValueNotGiven()
    {
        $this->propertyReflection
            ->shouldReceive('isDefaultValueAvailable')
            ->andReturnFalse();

        $this->propertyReflection
            ->shouldReceive('getDefaultValue')
            ->never();

        $this->propertyReflection
            ->shouldReceive('hasType')
            ->andReturnFalse();

        $this->assertEquals('mixed', $this->sut->getValue());
    }

    public function testGetTypeShouldReturnCorrectType()
    {
        $type = Mockery::mock(\ReflectionType::class);

        $type
            ->shouldReceive('getName')
            ->andReturn('typeName');

        $this->propertyReflection
            ->shouldReceive('hasType')
            ->andReturnTrue();

        $this->propertyReflection
            ->shouldReceive('getType')
            ->andReturn($type);

        $this->assertEquals('typeName', $this->sut->getType());
    }

    public function testGetTypeShouldReturnUnknownType()
    {
        $this->propertyReflection
            ->shouldReceive('hasType')
            ->andReturnFalse();

        $this->assertEquals('mixed', $this->sut->getType());
    }

    public function testGetShortNameShouldReturnCorrectValue()
    {
        $class = Mockery::mock(\ReflectionClass::class);

        $class
            ->shouldReceive('getShortName')
            ->andReturn('shortName');

        $this->propertyReflection
            ->shouldReceive('getClass')
            ->andReturn($class);

        $this->assertEquals('shortName', $this->sut->getShortName());
    }

    public function testGetShortNameShouldReturnFromTypeIfNotObject()
    {
        $this->propertyReflection
            ->shouldReceive('getClass')
            ->andReturnNull();

        $this->propertyReflection
            ->shouldReceive('hasType')
            ->once()
            ->andReturnFalse();

        $this->assertEquals('mixed', $this->sut->getShortName());
    }
}
