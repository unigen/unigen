<?php
declare(strict_types=1);

namespace UniGen\Config;

use UniGen\Config\Exception\SchemaNotFoundException;
use UniGen\Util\FileReader\FileReaderException;
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
     * @throws SchemaNotFoundException
     */
    public function create(int $version): Schema
    {
        $schemaPath = $this->schemaDir . DIRECTORY_SEPARATOR . $version . '.json';

        try {
            $content = JsonFileReader::getContent($schemaPath);
        } catch (FileReaderException $exception) {
            throw new SchemaNotFoundException(
                sprintf('Unable to load schema version #%d.', $version),
                0,
                $exception
            );
        }

        return new Schema($content);
    }

    /**
     * @return Schema
     *
     * @throws SchemaNotFoundException
     */
    public function createLatestSchema(): Schema
    {
        return $this->create(Schema::LATEST_VERSION);
    }
}