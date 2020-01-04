<?php

namespace UniGen\Config;

use UniGen\Config\Exception\ConfigSourceException;
use UniGen\Config\Source\JsonFileSource;

class ConfigFactory
{
    /** @var Schema */
    private $schema;

    /**
     * @param Schema
     */
    public function __construct(Schema $schema)
    {
        $this->schema = $schema;
    }

    /**
     * @return Config
     */
    public static function createDefault(): Config
    {
        return new Config([
            'testPath' => 'tests/unit/<dirname>/<filename>Test.<extension>',
            'testNamespace' => 'Test\Unit\<namespace>',
            'testCaseClass' => 'TestCase',
            'mockFramework' => 'mockery',
            'template' => realpath(__DIR__ . '/../Resources/views/sut_template.php.twig')
        ]);
    }

    /**
     * @param string $configPath
     *
     * @return Config
     *
     * @throws ConfigSourceException
     */
    public function createFromJsonFile(string $configPath): Config
    {
        $content = (new JsonFileSource())->fetch($configPath)->getContent();
        $this->schema->validate($content);

        return self::createDefault()->merge($content);
    }
}
