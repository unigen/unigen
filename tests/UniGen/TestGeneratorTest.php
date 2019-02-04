<?php

namespace UniGen\Test;

use Mockery;
use UniGen\Config;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use UniGen\Sut\SutInterface;
use UniGen\Sut\SutProviderInterface;
use UniGen\Renderer\RendererInterface;
use UniGen\FileSystem\FileSystemInterface;
use UniGen\TestGenerator;

class TestGeneratorTest extends TestCase
{
    /** @var Config|MockInterface */
    private $config;

    /** @var RendererInterface|MockInterface */
    private $renderer;

    /** @var FileSystemInterface|MockInterface */
    private $fileSystem;

    /** @var SutProviderInterface|MockInterface */
    private $sutProvider;

    /** @var SutInterface|MockInterface */
    private $sutMock;

    /** @var TestGenerator */
    private $sut;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->config = Mockery::mock(Config::class);
        $this->sutMock = Mockery::mock(SutInterface::class);
        $this->renderer = Mockery::mock(RendererInterface::class);
        $this->fileSystem = Mockery::mock(FileSystemInterface::class);
        $this->sutProvider = Mockery::mock(SutProviderInterface::class);

        $this->sut = new TestGenerator(
            $this->config,
            $this->renderer,
            $this->fileSystem,
            $this->sutProvider
        );
    }

    public function testGenerateShouldThrowExceptionWhenFileDoesNotExist()
    {
        $this->fileSystem
            ->shouldReceive('exist')
            ->with('/invalid/path')
            ->andReturnFalse();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Class to test does not exist in path /invalid/path');

        $this->sut->generate('/invalid/path');
    }

    public function testGenerateShouldThrowExceptionWhenTestAlreadyExist()
    {
        $this->fileSystem
            ->shouldReceive('exist')
            ->with('/valid/path')
            ->andReturnTrue();

        $this->fileSystem
            ->shouldReceive('exist')
            ->with('/valid/path/test')
            ->andReturnTrue();

        $this->sutMock
            ->shouldReceive('getPath')
            ->andReturn('/valid/path');

        $this->config
            ->shouldReceive('targetPathPattern')
            ->andReturn('/(.+)/');

        $this->config
            ->shouldReceive('targetPathReplacementPattern')
            ->andReturn('${1}/test');

        $this->fileSystem
            ->shouldReceive('read')
            ->with('/valid/path')
            ->andReturn('<?php class ExampleClass{} ?>php');

        $this->sutProvider
            ->shouldReceive('provide')
            ->with('\ExampleClass')
            ->andReturn($this->sutMock);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Test file /valid/path/test already exist');

        $this->sut->generate('/valid/path');
    }

    public function testGenerateShouldGenerateTest()
    {
        $this->fileSystem
            ->shouldReceive('exist')
            ->with('/valid/path')
            ->andReturnTrue();

        $this->fileSystem
            ->shouldReceive('exist')
            ->with('/valid/path/test')
            ->andReturnFalse();

        $this->renderer
            ->shouldReceive('render')
            ->with($this->sutMock)
            ->andReturn('testContent');

        $this->fileSystem
            ->shouldReceive('write')
            ->with('/valid/path/test', 'testContent');

        $this->sutMock
            ->shouldReceive('getPath')
            ->andReturn('/valid/path');

        $this->config
            ->shouldReceive('targetPathPattern')
            ->andReturn('/(.+)/');

        $this->config
            ->shouldReceive('targetPathReplacementPattern')
            ->andReturn('${1}/test');

        $this->fileSystem
            ->shouldReceive('read')
            ->with('/valid/path')
            ->andReturn('<?php class ExampleClass{} ?>php');

        $this->sutProvider
            ->shouldReceive('provide')
            ->with('\ExampleClass')
            ->andReturn($this->sutMock);

        $this->sut->generate('/valid/path');
    }
}
