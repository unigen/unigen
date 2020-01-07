<?php
declare(strict_types = 1);

namespace UniGen\Generator\Exception;

use UniGen\Sut\Exception\GeneratorException;

class TestGeneratorException extends GeneratorException
{
    public const CODE_NO_SOURCE_FILES = 1;
    public const CODE_NO_EXISTING_SOURCE_FILES = 2;
    public const CODE_TEST_EXISTS = 3;
}
