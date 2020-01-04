<?php

namespace UniGen\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use UniGen\Command\Exception\TestGeneratorException;
use UniGen\Config\ConfigFactory;
use UniGen\Config\Exception\ConfigException;
use UniGen\Config\Exception\SchemaException;
use UniGen\Generator\Exception\TestExistsException as GeneratorTestExistsException;
use UniGen\Generator\GeneratorFactory;
use UniGen\SourceFileCollection;

class TestGeneratorCommand extends Command
{
    const NAME = 'unigen:generate';

    const OPTION_CONFIG_FILE = 'config';
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
    protected function configure()
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
            ->addArgument(self::ARG_FILES, InputArgument::IS_ARRAY);
    }

    /**
     * {@inheritdoc}
     * @throws TestGeneratorException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sourceFileCollection = new SourceFileCollection($this->getSourceFiles($input));
        if (!$sourceFileCollection->hasSome()) {
            throw new TestGeneratorException(
                'No source file(s).',
                TestGeneratorException::CODE_NO_SOURCE_FILES
            );
        }

        if ($sourceFileCollection->hasMissing()) {
           throw new TestGeneratorException(
               sprintf('Source files do not exist: %s', json_encode($sourceFileCollection->getMissing())),
               TestGeneratorException::CODE_NO_EXISTING_SOURCE_FILES
           );
        }

        $configPath = $this->getConfigFile($input);
        if ($configPath === null) {
            $output->writeln('No config file. Default configuration applied.');
        }

        try {
            $config = $configPath
                ? $this->configFactory->createFromFile($configPath)
                : $this->configFactory->createDefault();
        } catch (ConfigException | SchemaException $exception) {
            // todo handle this better
            throw new TestGeneratorException("TODO", 0 , $exception);
        }


        try {
            $generator = $this->generatorFactory->create($config);
            foreach ($sourceFileCollection->getExisting() as $sourceFile) {
                $result = $generator->generate($sourceFile);
            }
            $output->writeln("<info>Test file {$result->getTestPath()} has been generated successfully</info>");
        } catch (GeneratorTestExistsException $exception) {
            throw new TestGeneratorException(
                sprintf('Test file "%s" already exists', $exception->getTestPath()),
                TestGeneratorException::CODE_TEST_EXISTS,
                $exception
            );
        }

        return 0;
    }


    /**
     * @param InputInterface $input
     *
     * @return string
     */
    private function getConfigFile(InputInterface $input): ?string
    {
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
        return $input->getArgument(self::ARG_FILES);
    }
}
