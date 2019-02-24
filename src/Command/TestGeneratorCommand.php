<?php

namespace UniGen\Command;

use UniGen\Config\Config;
use UniGen\Sut\SutInterface;
use UniGen\Util\ClassNameResolver;
use UniGen\Sut\SutProviderInterface;
use UniGen\Renderer\RendererInterface;
use UniGen\FileSystem\FileSystemInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestGeneratorCommand extends Command
{
    const CODE_ERROR = 1;
    const CODE_SUCCESS = 0;

    const NAME = 'unigen:generate';

    /** @var Config */
    private $config;

    /** @var RendererInterface */
    private $renderer;

    /** @var FileSystemInterface */
    private $fileSystem;

    /** @var SutProviderInterface */
    private $sutProvider;

    /**
     * @param Config               $config
     * @param RendererInterface    $renderer
     * @param FileSystemInterface  $fileSystem
     * @param SutProviderInterface $sutProvider
     */
    public function __construct(
        Config $config,
        RendererInterface $renderer,
        FileSystemInterface $fileSystem,
        SutProviderInterface $sutProvider
    ) {
        $this->config = $config;
        $this->renderer = $renderer;
        $this->fileSystem = $fileSystem;
        $this->sutProvider = $sutProvider;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->addArgument('path', InputArgument::REQUIRED)
            ->addOption('testCase', 't', InputOption::VALUE_REQUIRED)
            ->addOption('pathPattern', 'p', InputOption::VALUE_REQUIRED)
            ->addOption('mockFramework', 'f', InputOption::VALUE_REQUIRED)
            ->addOption('template', 'b', InputOption::VALUE_REQUIRED)
            ->addOption('templateDir', 'd', InputOption::VALUE_REQUIRED)
            ->addOption('namespacePattern', 'l', InputOption::VALUE_REQUIRED)
            ->addOption('pathPatternReplacement', 'z', InputOption::VALUE_REQUIRED)
            ->addOption('namespacePatternReplacement', 'x', InputOption::VALUE_REQUIRED);
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->config->merge($input->getOptions());
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path');

        if (!$this->fileSystem->exist($path)) {
            $output->writeln("<error>Class to test does not exist in path {$path}</error>");

            return self::CODE_ERROR;
        }

        $sut = $this->retrieveSut($path);
        $testPath = $this->retrieveTestTargetPath($sut);

        if ($this->fileSystem->exist($testPath)) {
            $output->writeln("Test file {$testPath} already exist");

            return self::CODE_ERROR;
        }

        $this->fileSystem->write($testPath, $this->renderer->render($sut));

        $output->writeln("<info>Test file {$testPath} has been generated successfully</info>");

        return self::CODE_SUCCESS;
    }

    /**
     * @param string $path
     *
     * @return SutInterface
     */
    private function retrieveSut(string $path): SutInterface
    {
        return $this->sutProvider->provide(ClassNameResolver::resolve($this->fileSystem->read($path)));
    }

    /**
     * @param SutInterface $sut
     *
     * @return string
     */
    private function retrieveTestTargetPath(SutInterface $sut): string
    {
        return preg_replace(
            $this->config->get('pathPattern'),
            $this->config->get('pathPatternReplacement'),
            $sut->getPath()
        );
    }
}
