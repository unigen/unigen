<?php

namespace UniGen\Test\Sut\Adapter;

use Mockery;
use ReflectionClass;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use UniGen\Sut\Adapter\ReflectionSutAdapter;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use UniGen\Sut\Adapter\ReflectionSutDependencyAdapter;
use UniGen\Sut\SutDependencyInterface;

class ReflectionSutAdapterTest extends TestCase
{
    const SUT_NAME = 'sutName';
    const SUT_FILE_PATH = '/file/path';
    const SUT_NAMESPACE = 'sutNamespace';
    const SUT_SHORT_NAME = 'sutShortName';

    use MockeryPHPUnitIntegration;

    /** @var ReflectionClass|MockInterface */
    private $reflection;

    /** @var ReflectionSutAdapter */
    private $sut;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->reflection = Mockery::mock(ReflectionClass::class);

        $this->sut = new ReflectionSutAdapter(
            $this->reflection
        );
    }

    public function testIsInterfaceShouldReturnTrue()
    {
        $this->reflection
            ->shouldReceive('isInterface')
            ->andReturnTrue();

        $this->assertTrue($this->sut->isInterface());
    }

    public function testIsInterfaceShouldReturnFalse()
    {
        $this->reflection
            ->shouldReceive('isInterface')
            ->andReturnFalse();

        $this->assertFalse($this->sut->isInterface());
    }

    public function testIsTraitShouldReturnTrue()
    {
        $this->reflection
            ->shouldReceive('isTrait')
            ->andReturnTrue();

        $this->assertTrue($this->sut->isTrait());
    }

    public function testIsTraitShouldReturnFalse()
    {
        $this->reflection
            ->shouldReceive('isTrait')
            ->andReturnFalse();

        $this->assertFalse($this->sut->isTrait());
    }

    public function testIsAbstractShouldReturnTrue()
    {
        $this->reflection
            ->shouldReceive('IsAbstract')
            ->andReturnTrue();

        $this->assertTrue($this->sut->IsAbstract());
    }

    public function testIsAbstractShouldReturnFalse()
    {
        $this->reflection
            ->shouldReceive('IsAbstract')
            ->andReturnFalse();

        $this->assertFalse($this->sut->IsAbstract());
    }

    public function testGetNameShouldReturnCorrectName()
    {
        $this->reflection
            ->shouldReceive('getName')
            ->andReturn(self::SUT_NAME);

        $result = $this->sut->getName();

        $this->assertInternalType('string', $result);
        $this->assertSame(self::SUT_NAME, $result);
    }

    public function testGetShortNameShouldReturnCorrectShortName()
    {
        $this->reflection
            ->shouldReceive('getShortName')
            ->andReturn(self::SUT_SHORT_NAME);

        $result = $this->sut->getShortName();

        $this->assertInternalType('string', $result);
        $this->assertSame(self::SUT_SHORT_NAME, $result);
    }

    public function testHasNamespaceShouldReturnTrueWhenNamespaceIsGiven()
    {
        $this->reflection
            ->shouldReceive('getNamespaceName')
            ->andReturn(self::SUT_NAMESPACE);

        $this->assertTrue($this->sut->hasNamespace());
    }

    public function testHasNamespaceShouldReturnFalseWhenNamespaceIsEmpty()
    {
        $this->reflection
            ->shouldReceive('getNamespaceName')
            ->andReturn('');

        $this->assertFalse($this->sut->hasNamespace());
    }

    public function testGetNamespaceShouldReturnCorrectNamespace()
    {
        $this->reflection
            ->shouldReceive('getNamespaceName')
            ->andReturn(self::SUT_NAMESPACE);

        $result = $this->sut->getNamespace();

        $this->assertInternalType('string', $result);
        $this->assertEquals(self::SUT_NAMESPACE, $result);
    }

    public function testGetPathShouldReturnCorrectFilePath()
    {
        $this->reflection
            ->shouldReceive('getFileName')
            ->andReturn(self::SUT_FILE_PATH);

        $result = $this->sut->getPath();

        $this->assertInternalType('string', $result);
        $this->assertEquals(self::SUT_FILE_PATH, $result);
    }

    public function testHasDependenciesShouldReturnFalseWhenSutDoesNotHaveConstructor()
    {
        $this->reflection
            ->shouldReceive('getConstructor')
            ->andReturnNull();

        $this->assertFalse($this->sut->hasDependencies());
    }

    public function testHasDependenciesShouldReturnTrueWhenConstructorHasParameters()
    {
        $constructor = Mockery::mock(\ReflectionMethod::class);
        $parameters = Mockery::mock(\ReflectionParameter::class);

        $this->reflection
            ->shouldReceive('getConstructor')
            ->andReturn($constructor);

        $constructor
            ->shouldReceive('getParameters')
            ->andReturn([$parameters]);

        $this->assertTrue($this->sut->hasDependencies());
    }

    public function testGetDependenciesShouldReturnEmptyArrayWhenConstructorDoesNotExist()
    {
        $this->reflection
            ->shouldReceive('getConstructor')
            ->andReturnNull();

        $result = $this->sut->getDependencies();

        $this->assertEmpty($result);
        $this->assertInternalType('array', $result);
    }

    public function testGetDependenciesShouldReturnEmptyArrayWhenConstructorParametersAreNotExist()
    {
        $constructor = Mockery::mock(\ReflectionMethod::class);

        $constructor
            ->shouldReceive('getParameters')
            ->andReturn([]);

        $this->reflection
            ->shouldReceive('getConstructor')
            ->andReturn($constructor);

        $result = $this->sut->getDependencies();

        $this->assertEmpty($result);
        $this->assertInternalType('array', $result);
    }

    public function testGetDependenciesShouldReturnDependencies()
    {
        $constructor = Mockery::mock(\ReflectionMethod::class);
        $parameter = Mockery::mock(\ReflectionParameter::class);

        $constructor
            ->shouldReceive('getParameters')
            ->andReturn([$parameter]);

        $this->reflection
            ->shouldReceive('getConstructor')
            ->andReturn($constructor);

        $result = $this->sut->getDependencies();

        $this->assertInternalType('array', $result);
        $this->assertInstanceOf(SutDependencyInterface::class, $result[0]);
        $this->assertInstanceOf(ReflectionSutDependencyAdapter::class, $result[0]);
    }

    public function testGetPublicMethodsShouldReturnEmptyArrayWhenPublicMethodsDoesNotExist()
    {
        $this->reflection
            ->shouldReceive('getMethods')
            ->with(\ReflectionMethod::IS_PUBLIC)
            ->andReturn([]);

        $result = $this->sut->getPublicMethods();

        $this->assertEmpty($result);
        $this->assertInternalType('array', $result);
    }

    public function testGetPublicMethodsShouldReturnMethodsExceptConstructor()
    {
        $declaringClass = Mockery::mock(ReflectionClass::class);
        $regularMethod = Mockery::mock(\ReflectionMethod::class);
        $constructorMethod = Mockery::mock(\ReflectionMethod::class);

        $this->reflection
            ->shouldReceive('getName')
            ->andReturn('className');

        $regularMethod
            ->shouldReceive('getDeclaringClass')
            ->andReturn($declaringClass);

        $declaringClass
            ->shouldReceive('getName')
            ->andReturn('className');

        $regularMethod
            ->shouldReceive('isConstructor')
            ->andReturnFalse();

        $regularMethod
            ->shouldReceive('getName')
            ->andReturn('regularMethod');

        $constructorMethod
            ->shouldReceive('isConstructor')
            ->andReturnTrue();

        $constructorMethod
            ->shouldReceive('getName')
            ->andReturn('__constructor');

        $this->reflection
            ->shouldReceive('getMethods')
            ->with(\ReflectionMethod::IS_PUBLIC)
            ->andReturn([
                $regularMethod,
                $constructorMethod,
            ]);

        $result = $this->sut->getPublicMethods();

        $this->assertCount(1, $result);
        $this->assertInternalType('array', $result);
        $this->assertEquals('regularMethod', $result[0]);
    }

    public function testGetPublicMethodsShouldNotReturnMethodsFromParent()
    {
        $declaringClass = Mockery::mock(ReflectionClass::class);
        $declaringParentClass = Mockery::mock(ReflectionClass::class);
        $regularMethod = Mockery::mock(\ReflectionMethod::class);
        $parentMethod = Mockery::mock(\ReflectionMethod::class);
        $constructorMethod = Mockery::mock(\ReflectionMethod::class);

        $this->reflection
            ->shouldReceive('getName')
            ->andReturn('className');

        $regularMethod
            ->shouldReceive('getDeclaringClass')
            ->andReturn($declaringClass);

        $declaringClass
            ->shouldReceive('getName')
            ->andReturn('className');

        $declaringParentClass
            ->shouldReceive('getName')
            ->andReturn('parentClass');

        $parentMethod
            ->shouldReceive('getDeclaringClass')
            ->andReturn($declaringParentClass);

        $parentMethod
            ->shouldReceive('isConstructor')
            ->andReturnFalse();

        $parentMethod
            ->shouldReceive('getName')
            ->andReturn('regularMethod');

        $regularMethod
            ->shouldReceive('isConstructor')
            ->andReturnFalse();

        $regularMethod
            ->shouldReceive('getName')
            ->andReturn('regularMethod');

        $constructorMethod
            ->shouldReceive('isConstructor')
            ->andReturnTrue();

        $constructorMethod
            ->shouldReceive('getName')
            ->andReturn('__constructor');

        $this->reflection
            ->shouldReceive('getMethods')
            ->with(\ReflectionMethod::IS_PUBLIC)
            ->andReturn([
                $parentMethod,
                $regularMethod,
                $constructorMethod,
            ]);

        $result = $this->sut->getPublicMethods();

        $this->assertCount(1, $result);
        $this->assertInternalType('array', $result);
        $this->assertEquals('regularMethod', $result[0]);
    }
}
