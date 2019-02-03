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
                break;
            case 'int':
                return '1';
                break;
            case 'bool':
                return 'true';
                break;
            case 'callable':
                return 'function () {}';
                break;
            case 'array':
                return '[]';
                break;
            default:
                return "'mixed'";
        }
    }
}
