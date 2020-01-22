<?php

namespace UniGen\Generator;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use UniGen\Config\ConfigFactory;
use UniGen\Generator\Exception\GeneratorException;
use UniGen\Share\Exception\UnigenException;

class GenerateCommand extends Command
{
    public const NAME = 'unigen:generate';

    private const OPTION_CONFIG_FILE = 'config';
    private const OPTION_OVERRIDE_FILE = 'override';
    private const ARG_FILES = 'source_files';

    private const CODE_SUCCESS = 0;

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
     * @throws UnigenException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $sourceFileCollection = new SourceFileCollection($this->getSourceFiles($input));
        $this->validateSourceFileCollection($sourceFileCollection);

        $configPath = $this->getConfigFile($input);
        $io->comment($configPath === null
            ? 'No config file. Default configuration applied.'
            : sprintf('Using config file "%s".', $configPath)
        );

        $config = $configPath
            ? $this->configFactory->createFromFile($configPath)
            : $this->configFactory->createDefault();

        $generator = $this->generatorFactory->create($config);
        foreach ($sourceFileCollection->getExisting() as $sourceFile) {
            $result = $generator->generate($sourceFile, $this->getOverrideFlag($input));
            $io->success(sprintf('Test file "%s" has been generated successfully', $result->getTestPath()));
        }

        return self::CODE_SUCCESS;
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
            throw new GeneratorException(
                sprintf('Source file "%s" does not exist.', $sourceFileCollection->getFirstMissing())
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
}
