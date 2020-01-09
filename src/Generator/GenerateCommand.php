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
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $sourceFileCollection = new SourceFileCollection($this->getSourceFiles($input));
        try {
            $this->validateSourceFileCollection($sourceFileCollection);
        } catch (GeneratorException $exception) {
            return $this->handleGeneratorException($exception, $io);
        }

        $configPath = $this->getConfigFile($input);
        if ($configPath === null) {
            $output->writeln('No config file. Default configuration applied.');
        }

        try {
            $config = $configPath
                ? $this->configFactory->createFromFile($configPath)
                : $this->configFactory->createDefault();
        } catch (ConfigException $exception) {
            return $this->handleConfigException($exception, $io);
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
            return $this->handleConfigException($exception, $io);
        } catch (GeneratorException $exception) {
            return $this->handleGeneratorException($exception, $io);
        } catch (RendererException $exception) {
            return $this->handleRendererException($exception, $io);
        } catch (SutException $exception) {
            return $this->handleSutException($exception, $io);
        }

        return 0;
    }

    /**
     * @param SourceFileCollection $sourceFileCollection
     *
     * @throws GeneratorException
     */
    private function validateSourceFileCollection(SourceFileCollection $sourceFileCollection): void
    {
        if (!$sourceFileCollection->hasSome()) {
            throw new GeneratorException('No source file(s).');
        }

        if ($sourceFileCollection->hasMissing()) {
            // TODO
            throw new GeneratorException(
                sprintf('Source files does not exist: %s', json_encode($sourceFileCollection->getMissing()))
            );
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
     * @param SymfonyStyle $io
     *
     * @return int
     */
    private function handleGeneratorException(GeneratorException $exception, SymfonyStyle $io): int
    {
        $io->error($exception->getMessage());

        return 1;
    }

    /**
     * @param ConfigException $exception
     * @param SymfonyStyle $io
     *
     * @return int
     */
    private function handleConfigException(ConfigException $exception, SymfonyStyle $io): int
    {
        $io->error($exception->getMessage());
        if ($exception instanceof InvalidConfigSchemaException) {
            $io->listing($exception->getViolations());
        }

        return 2;
    }

    /**
     * @param RendererException $exception
     *
     * @param SymfonyStyle $io
     * @return int
     */
    private function handleRendererException(RendererException $exception, SymfonyStyle $io): int
    {
        $io->error($exception->getMessage());

        return 3;
    }

    /**
     * @param SutException $exception
     *
     * @param SymfonyStyle $io
     * @return int
     */
    private function handleSutException(SutException $exception, SymfonyStyle $io): int
    {
        $io->error($exception->getMessage());

        return 4;
    }
}
