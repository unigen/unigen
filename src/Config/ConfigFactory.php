<?php

namespace UniGen\Config;


use UniGen\Config\Exception\ConfigException;
use UniGen\Config\Exception\SchemaException;
use UniGen\Util\FileLoader\FileLoaderException;
use UniGen\Util\FileLoader\JsonFileLoader;

class ConfigFactory
{
    /** @var SchemaFactory */
    private $schemaFactory;

    /**
     * @param SchemaFactory $schemaFactory
     */
    public function __construct(SchemaFactory $schemaFactory)
    {
        $this->schemaFactory = $schemaFactory;
    }

    /**
     * @return Config
     *
     * @throws SchemaException
     * @throws ConfigException
     */
    public function createDefault(): Config
    {
        return new Config(
            $this->getDefaultParameters(),
            $this->schemaFactory->createLatestSchema()
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function getDefaultParameters(): array
    {
        return [
            'version' => Schema::LATEST_VERSION,
            'testPath' => 'tests/unit/<dirname(1)>/<filename>Test.<extension>',
            'testNamespace' => 'Test\Unit\<namespace>',
            'testCaseClass' => 'TestCase',
            'mockFramework' => 'mockery',
            'template' => realpath(__DIR__ . '/../Resources/views/sut_template.php.twig')
        ];
    }

    /**
     * @param string $configPath
     *
     * @return Config
     *
     * @throws ConfigException
     * @throws SchemaException
     */
    public function createFromFile(string $configPath): Config
    {
        try {
            $content = JsonFileLoader::getContent($configPath);
        } catch (FileLoaderException $exception) {
            throw new ConfigException(
                sprintf('Unable to load config "%s".', $configPath),
                0,
                $exception
            );
        }

        $parameters = array_merge($this->getDefaultParameters(), $content);

        return new Config($parameters, $this->schemaFactory->create($parameters['version']));
    }

}
