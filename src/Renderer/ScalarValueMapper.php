<?php
declare(strict_types=1);

namespace UniGen\Renderer;

class ScalarValueMapper
{
    /**
     * @param mixed $type
     *
     * @return string
     */
    public function map($type): string
    {
        if (is_string($type)) {
            return '\'string\'';
        }

        if (is_int($type)) {
            return '1';
        }

        if (is_array($type)) {
            return '[]';
        }

        if (is_bool($type)) {
            return 'true';
        }

        if (is_callable($type)) {
            return 'function(){}';
        }

        if (is_null($type)) {
            return 'null';
        }

        if (is_float($type)) {
            return '0.0';
        }

        return '\'mixed\'';
    }
}
