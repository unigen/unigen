<?php

declare(strict_types=1);

namespace UniGen\Util;

class ScalarValueResolver
{
    /**
     * @param string $type
     *
     * @return mixed
     */
    public static function resolve(string $type): string
    {
        switch ($type) {
            case 'string':
                return "'string'";
            case 'int':
                return '1';
            case 'bool':
                return 'true';
            case 'callable':
                return 'function () {}';
            case 'array':
                return '[]';
            default:
                return "'mixed'";
        }
    }
}
