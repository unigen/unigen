<?php
/**
 * This file is part of Boozt Platform
 * and belongs to Boozt Fashion AB.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

declare(strict_types = 1);

namespace UniGen\Generator\Exception;

use UniGen\Sut\Exception\GeneratorException;

/** Class TestGeneratorException */
class TestGeneratorException extends GeneratorException
{
    public const CODE_NO_SOURCE_FILES = 1;
    public const CODE_NO_EXISTING_SOURCE_FILES = 2;
    public const CODE_TEST_EXISTS = 3;
}
