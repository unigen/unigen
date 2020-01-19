<?php
declare(strict_types=1);

namespace UniGen\Config;

use UniGen\Config\Exception\ConfigException;
use UniGen\Util\Exception\FileReaderException;
use UniGen\Util\FileReader\JsonFileReader;

class SchemaFactory
{
    /** @var string */
    private $schemaDir;

    /**
     * @param string $schemaDir
     */
    public function __construct(string $schemaDir)
    {
        $this->schemaDir = $schemaDir;
    }

    /**
     * @param int $version
     *
     * @return Schema
     *
     * @throws ConfigException
     */
    public function create(int $version): Schema
    {
        try {
            $content = JsonFileReader::getContent($this->getSchemaPath($version));
        } catch (FileReaderException $exception) {
            throw new ConfigException(
                sprintf('Unable to load schema version #%d.', $version),
                0,
                $exception
            );
        }

        return new Schema($content);
    }

    /**
     * @param int $version
     *
     * @return string
     */
    private function getSchemaPath(int $version): string
    {
        return $this->schemaDir . DIRECTORY_SEPARATOR . $version . '.json';
    }

    /**
     * @return Schema
     *
     * @throws ConfigException
     */
    public function createLatestSchema(): Schema
    {
        return $this->create(Schema::LATEST_VERSION);
    }
}
