<?php
declare(strict_types=1);

namespace UniGen\Config;

use UniGen\Config\Exception\SchemaException;
use UniGen\Util\Exception\FileLoaderException;
use UniGen\Util\JsonFileLoader;

class SchemaFactory
{
    /** @var string */
    private $schemaDir;

    /**
     * @param string $schemaDir
     */
    public function __construct(string $schemaDir)
    {
        $this->schemaDir = realpath($schemaDir);
    }

    /**
     * @param int $version
     *
     * @return Schema
     *
     * @throws SchemaException
     */
    public function create(int $version): Schema
    {
        $schemaPath = $this->schemaDir . DIRECTORY_SEPARATOR . $version . '.json';

        try {
            $content = JsonFileLoader::getContent($schemaPath);
        } catch (FileLoaderException $exception) {
            throw new SchemaException(
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
     * @throws SchemaException
     */
    public function createLatestSchema(): Schema
    {
        return $this->create(Schema::LATEST_VERSION);
    }
}