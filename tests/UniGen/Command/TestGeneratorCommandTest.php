<?php

namespace UniGen\Test\Command;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;
use UniGen\Command\TestGeneratorCommand;
use UniGen\Config\Config;
use UniGen\FileSystem\FileSystemInterface;
use UniGen\Renderer\RendererInterface;
use UniGen\Sut\SutInterface;
use UniGen\Sut\SutProviderInterface;

class TestGeneratorCommandTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var Application */
    private $application;

    /** @var Config|MockInterface */
    private $configMock;

    /** @var RendererInterface|MockInterface */
    private $rendererMock;

    /** @var FileSystemInterface|MockInterface */
    private $fileSystemMock;

    /** @var SutProviderInterface|MockInterface */
    private $sutProviderMock;

    /** @var SutInterface|MockInterface */
    private $sutMock;

    /** @var TestGeneratorCommand */
    private $sut;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->application = new Application();

        $this->configMock = Mockery::mock(Config::class);
        $this->sutMock = Mockery::mock(SutInterface::class);
        $this->rendererMock = Mockery::mock(RendererInterface::class);
        $this->fileSystemMock = Mockery::mock(FileSystemInterface::class);
        $this->sutProviderMock = Mockery::mock(SutProviderInterface::class);

        $this->sut = new TestGeneratorCommand(
            $this->configMock,
            $this->rendererMock,
            $this->fileSystemMock,
            $this->sutProviderMock
        );

        $this->application->add($this->sut);
    }

    /**
     * @dataProvider defaultOptionsProvider
     *
     * @param array $defaultOptions
     */
    public function testShouldMergeDefaultOptionsWhenNoneIsGiven(array $defaultOptions)
    {
        $this->configMock
            ->shouldReceive('merge')
            ->with($defaultOptions);

        $this->expectExceptionMessage('Not enough arguments (missing: "path").');
        $this->expectException(RuntimeException::class);

        (new CommandTester($this->application->find('unigen:generate')))->execute([]);
    }

    /**
     * @dataProvider optionsProvider
     *
     * @param array $expected
     * @param array $inputOptions
     */
    public function testShouldMergeCorrectOptionsWhenGiven(array $expected, array $inputOptions)
    {
        $this->configMock
            ->shouldReceive('merge')
            ->with($expected);

        $this->expectExceptionMessage('Not enough arguments (missing: "path").');
        $this->expectException(RuntimeException::class);

        (new CommandTester($this->application->find('unigen:generate')))->execute($inputOptions);
    }

    public function testShouldFailWhenPathIsNotGiven()
    {
        $this->configMock
            ->shouldReceive('merge');

        $this->expectExceptionMessage('Not enough arguments (missing: "path").');
        $this->expectException(RuntimeException::class);

        (new CommandTester($this->application->find('unigen:generate')))->execute([]);
    }

    public function testShouldFailWhenClassToTestDoesNotExistInGivenPath()
    {
        $this->configMock->shouldReceive('merge');

        $this->fileSystemMock
            ->shouldReceive('exist')
            ->with('invalid-path')
            ->andReturnFalse();

        $commandTester = new CommandTester($this->application->find('unigen:generate'));

        $commandTester->execute(['path' => 'invalid-path']);

        $this->assertEquals(1, $commandTester->getStatusCode());
        $this->assertEquals("Class to test does not exist in path invalid-path\n", $commandTester->getDisplay(true));
    }

    public function testShouldFailWhenTestIsAlreadyGenerated()
    {
        $this->configMock->shouldReceive('merge');

        $this->fileSystemMock
            ->shouldReceive('exist')
            ->with('valid-path')
            ->andReturnTrue();

        $this->fileSystemMock
            ->shouldReceive('read')
            ->with('valid-path')
            ->andReturn('class ExampleClass');

        $this->sutProviderMock
            ->shouldReceive('provide')
            ->with('\ExampleClass')
            ->andReturn($this->sutMock);

        $this->configMock
            ->shouldReceive('get')
            ->with('pathPattern')
            ->andReturn('/(.+)/');

        $this->configMock
            ->shouldReceive('get')
            ->with('pathPatternReplacement')
            ->andReturn('${1}-test');

        $this->sutMock
            ->shouldReceive('getPath')
            ->andReturn('valid-path');

        $this->fileSystemMock
            ->shouldReceive('exist')
            ->with('valid-path-test')
            ->andReturnTrue();

        $commandTester = new CommandTester($this->application->find('unigen:generate'));

        $commandTester->execute(['path' => 'valid-path']);

        $this->assertEquals(1, $commandTester->getStatusCode());
        $this->assertEquals("Test file valid-path-test already exist\n", $commandTester->getDisplay(true));
    }

    public function testShouldGenerateTestSuccessfully()
    {
        $this->configMock->shouldReceive('merge');

        $this->fileSystemMock
            ->shouldReceive('exist')
            ->with('valid-path')
            ->andReturnTrue();

        $this->fileSystemMock
            ->shouldReceive('read')
            ->with('valid-path')
            ->andReturn('class ExampleClass');

        $this->sutProviderMock
            ->shouldReceive('provide')
            ->with('\ExampleClass')
            ->andReturn($this->sutMock);

        $this->configMock
            ->shouldReceive('get')
            ->with('pathPattern')
            ->andReturn('/(.+)/');

        $this->configMock
            ->shouldReceive('get')
            ->with('pathPatternReplacement')
            ->andReturn('${1}-test');

        $this->sutMock
            ->shouldReceive('getPath')
            ->andReturn('valid-path');

        $this->fileSystemMock
            ->shouldReceive('exist')
            ->with('valid-path-test')
            ->andReturnFalse();

        $this->rendererMock
            ->shouldReceive('render')
            ->with($this->sutMock)
            ->andReturn('test-content');

        $this->fileSystemMock
            ->shouldReceive('write')
            ->with('valid-path-test', 'test-content');

        $commandTester = new CommandTester($this->application->find('unigen:generate'));

        $commandTester->execute(['path' => 'valid-path']);

        $this->assertEquals(0, $commandTester->getStatusCode());
        $this->assertEquals(
            "Test file valid-path-test has been generated successfully\n",
            $commandTester->getDisplay()
        );
    }

    /**
     * @return array
     */
    public function defaultOptionsProvider(): array
    {
        return [
            [
                [
                    'testCase' => null,
                    'pathPattern' => null,
                    'mockFramework' => null,
                    'template' => null,
                    'templateDir' => null,
                    'namespacePattern' => null,
                    'pathPatternReplacement' => null,
                    'namespacePatternReplacement' => null,
                    'help' => false,
                    'quiet' => false,
                    'verbose' => false,
                    'version' => false,
                    'ansi' => false,
                    'no-ansi' => false,
                    'no-interaction' => false
                ],
            ]
        ];
    }

    /**
     * @return array
     */
    public function optionsProvider(): array
    {
        return [
            [
                [
                    'testCase' => 'example-test-case',
                    'pathPattern' => 'example-path-pattern',
                    'mockFramework' => 'example-framework',
                    'template' => 'example-template',
                    'templateDir' => 'example-dir',
                    'namespacePattern' => 'example-namespace-pattern',
                    'pathPatternReplacement' => 'example-pattern-rep',
                    'namespacePatternReplacement' => 'example-namespace-rep',
                    'help' => false,
                    'quiet' => false,
                    'verbose' => false,
                    'version' => false,
                    'ansi' => false,
                    'no-ansi' => false,
                    'no-interaction' => false
                ],
                [
                    '--testCase' => 'example-test-case',
                    '--pathPattern' => 'example-path-pattern',
                    '--mockFramework' => 'example-framework',
                    '--template' => 'example-template',
                    '--templateDir' => 'example-dir',
                    '--namespacePattern' => 'example-namespace-pattern',
                    '--pathPatternReplacement' => 'example-pattern-rep',
                    '--namespacePatternReplacement' => 'example-namespace-rep',
                ],
            ]
        ];
    }
}
