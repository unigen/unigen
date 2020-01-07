<?php
declare(strict_types=1);

namespace UniGen\Config;

use UniGen\Config\Exception\ConfigNotFoundException;
use UniGen\Config\Exception\InvalidConfigSchemaException;
use UniGen\Config\Exception\SchemaNotFoundException;
use UniGen\Util\FileReader\FileReaderException;
use UniGen\Util\FileReader\JsonFileReader;

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
     * @throws InvalidConfigSchemaException
     * @throws SchemaNotFoundException
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
     * @throws ConfigNotFoundException
     * @throws SchemaNotFoundException
     * @throws InvalidConfigSchemaException
     */
    public function createFromFile(string $configPath): Config
    {
        try {
            $content = JsonFileReader::getContent($configPath);
        } catch (FileReaderException $exception) {
            throw new ConfigNotFoundException(
                sprintf('Unable to load config "%s".', $configPath),
                0,
                $exception
            );
        }

        $parameters = array_merge($this->getDefaultParameters(), $content);

        return new Config($parameters, $this->schemaFactory->create($parameters['version']));
    }

}
