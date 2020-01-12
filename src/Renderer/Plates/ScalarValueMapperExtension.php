<?php
declare(strict_types=1);

namespace UniGen\Renderer\Plates;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use UniGen\Renderer\ScalarValueMapper;

class ScalarValueMapperExtension implements ExtensionInterface
{
    /** @var ScalarValueMapper */
    private $scalarValueMapper;

    /**
     * @param ScalarValueMapper $scalarValueMapper
     */
    public function __construct(ScalarValueMapper $scalarValueMapper)
    {
        $this->scalarValueMapper = $scalarValueMapper;
    }

    /**
     * @param Engine $engine
     */
    public function register(Engine $engine)
    {
        $engine->registerFunction('scalar', [$this, 'scalar']);
    }

    /**
     * @param mixed $var
     *
     * @return string
     */
    public function scalar($var): string
    {
        return $this->scalarValueMapper->map($var);
    }
}
