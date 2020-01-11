<?php

namespace UniGen\Generator;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use UniGen\Config\ConfigFactory;
use UniGen\Config\Exception\ConfigException;
use UniGen\Config\Exception\InvalidConfigSchemaException;
use UniGen\Generator\Exception\GenerateCommandException;
use UniGen\Generator\Exception\GeneratorException;
use UniGen\Generator\Exception\MissingSourceFileException;
use UniGen\Renderer\RendererException;
use UniGen\Sut\SutException;

class GenerateCommand extends BaseCommand
{
    const NAME = 'unigen:generate';

    const OPTION_CONFIG_FILE = 'config';
    const OPTION_OVERRIDE_FILE = 'override';
    const ARG_FILES = 'source_files';

    /** @var ConfigFactory */
    private $configFactory;

    /** @var GeneratorFactory */
    private $generatorFactory;

    /**
     * @param ConfigFactory $configFactory
     * @param GeneratorFactory $generator
     */
    public function __construct(ConfigFactory $configFactory, GeneratorFactory $generator)
    {
        $this->configFactory = $configFactory;
        $this->generatorFactory = $generator;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->addOption(
                self::OPTION_CONFIG_FILE,
                'c',
                InputOption::VALUE_REQUIRED,
                'config file path',
                '.unigen.json'
            )
            ->addOption(
                self::OPTION_OVERRIDE_FILE,
                'o',
                InputOption::VALUE_NONE,
                'override test file if already exists'
            )
            ->addArgument(self::ARG_FILES, InputArgument::IS_ARRAY);
    }

    /**
     * {@inheritdoc}
     *
     * @throws GenerateCommandException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $isVerbose = (new SymfonyStyle($input, $output))->isVerbose();

        $sourceFileCollection = new SourceFileCollection($this->getSourceFiles($input));
        try {
            $this->validateSourceFileCollection($sourceFileCollection);
        } catch (GeneratorException $exception) {
            throw $this->handleGeneratorException($exception, $isVerbose);
        }

        $configPath = $this->getConfigFile($input);
        if ($configPath === null) {
            $output->writeln($configPath === null
                ? '<info>No config file. Default configuration applied.</info>'
                : sprintf('Using config file "%s".', $configPath)
            );
        }

        try {
            $config = $configPath
                ? $this->configFactory->createFromFile($configPath)
                : $this->configFactory->createDefault();
        } catch (ConfigException $exception) {
            throw $this->handleConfigException($exception, $isVerbose);
        }

        try {
            $generator = $this->generatorFactory->create($config);
            foreach ($sourceFileCollection->getExisting() as $sourceFile) {
                $result = $generator->generate($sourceFile, $this->getOverrideFlag($input));

                $output->writeln(
                    sprintf('<info>Test file "%s" has been generated successfully</info>', $result->getTestPath())
                );
            }
        } catch (ConfigException $exception) {
            throw $this->handleConfigException($exception, $isVerbose);
        } catch (GeneratorException $exception) {
            throw $this->handleGeneratorException($exception, $isVerbose);
        } catch (RendererException $exception) {
            throw $this->handleRendererException($exception, $isVerbose);
        } catch (SutException $exception) {
            throw $this->handleSutException($exception, $isVerbose);
        }

        return 0;
    }

    /**
     * @param SourceFileCollection $sourceFileCollection
     *
     * @throws GeneratorException
     * @throws MissingSourceFileException
     */
    private function validateSourceFileCollection(SourceFileCollection $sourceFileCollection): void
    {
        if (!$sourceFileCollection->hasSome()) {
            throw new GeneratorException('No source file(s).');
        }

        if ($sourceFileCollection->hasMissing()) {
            $exception = new MissingSourceFileException('Source files does not exist.');
            $exception->setMissingFiles($sourceFileCollection->getMissing());

            throw $exception;
        }
    }

    /**
     * @param InputInterface $input
     *
     * @return string
     */
    private function getConfigFile(InputInterface $input): ?string
    {
        /** @var string $configParam */
        $configParam = $input->getOption(self::OPTION_CONFIG_FILE);
        $configPath = realpath($configParam);

        return $configPath === false
            ? null
            : $configPath;
    }

    /**
     * @param InputInterface $input
     *
     * @return string[]
     */
    private function getSourceFiles(InputInterface $input): array
    {
        /** @var string[] $sourceFiles */
        $sourceFiles = $input->getArgument(self::ARG_FILES);

        return $sourceFiles;
    }

    /**
     * @param InputInterface $input
     *
     * @return bool
     */
    private function getOverrideFlag(InputInterface $input): bool
    {
        /** @var bool $override */
        $override = $input->getOption(self::OPTION_OVERRIDE_FILE);

        return $override;
    }

    /**
     * @param GeneratorException $exception
     * @param bool $isVerbose
     *
     * @return GenerateCommandException
     */
    private function handleGeneratorException(GeneratorException $exception, bool $isVerbose): GenerateCommandException
    {
        $message = $exception->getMessage();
        if ($exception instanceof MissingSourceFileException) {
            $message = $this->createConsoleList(
                'The following source file(s) does not exists:',
                $exception->getMissingFiles()
            );
        }

        return new GenerateCommandException($message, 1, $isVerbose ? $exception : null);
    }

    /**
     * @param ConfigException $exception
     * @param bool $isVerbose
     *
     * @return GenerateCommandException
     */
    private function handleConfigException(ConfigException $exception, bool $isVerbose): GenerateCommandException
    {
        $message = $exception->getMessage();
        if ($exception instanceof InvalidConfigSchemaException) {
            $message = $this->createConsoleList(
                'Invalid config schema. Please fix the following violations:',
                $exception->getViolations()
            );
        }

        return new GenerateCommandException($message, 2, $isVerbose ? $exception : null);
    }

    /**
     * @param RendererException $exception
     * @param bool $isVerbose
     *
     * @return GenerateCommandException
     */
    private function handleRendererException(RendererException $exception, bool $isVerbose): GenerateCommandException
    {
        return new GenerateCommandException($exception->getMessage(), 3, $isVerbose ? $exception : null);
    }

    /**
     * @param SutException $exception
     * @param bool $isVerbose
     *
     * @return GenerateCommandException
     */
    private function handleSutException(SutException $exception, bool $isVerbose): GenerateCommandException
    {
        return new GenerateCommandException($exception->getMessage(), 4, $isVerbose ? $exception : null);
    }

    /**
     * @param string $header
     * @param string[] $elements
     *
     * @return string
     */
    private function createConsoleList(string $header, array $elements): string
    {
        $lines = [$header];
        foreach ($elements as $element) {
            $lines[] = '> ' . $element;
        }

        return implode("\n", $lines);
    }
}
