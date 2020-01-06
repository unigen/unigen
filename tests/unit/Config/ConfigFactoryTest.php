<?php

namespace Test\Unit\UniGen\Config;

use UniGen\Config\SchemaFactory;
use Mockery;
use Mockery\MockInterface as MockObject;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use UniGen\Config\ConfigFactory;

final class ConfigFactoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var SchemaFactory|MockObject */
    private $schemaFactoryMock;

    /** @var ConfigFactory */
    private $sut;

    /**
     * {@inheritdoc}
    */
    public function setUp()
    {
        $this->schemaFactoryMock = Mockery::mock(SchemaFactory::class);

        $this->sut = new ConfigFactory(
            $this->schemaFactoryMock    
        );
    }

    public function testCreateDefault()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function testCreateFromFile()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }
}
