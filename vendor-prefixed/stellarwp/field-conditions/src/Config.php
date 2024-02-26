<?php
/**
 * @license GPL-2.0-or-later
 *
 * Modified by learndash on 22-September-2023 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace StellarWP\Learndash\StellarWP\FieldConditions;

use InvalidArgumentException;

/**
 * Sets up the Field Condition library. It currently only provides an optional means of replacing an exception.
 *
 * @since 1.0.0
 */
class Config
{
    /**
     * @var class-string<InvalidArgumentException>
     */
    private static $invalidArgumentExceptionClass = InvalidArgumentException::class;

    /**
     * @since 1.0.0
     *
     * @throws InvalidArgumentException
     */
    public static function throwInvalidArgumentException()
    {
        throw new self::$invalidArgumentExceptionClass(...func_get_args());
    }

    /**
     * @since 1.0.0
     *
     * @return class-string<InvalidArgumentException>
     */
    public static function getInvalidArgumentExceptionClass(): string
    {
        return self::$invalidArgumentExceptionClass;
    }

    /**
     * @since 1.0.0
     *
     * @param class-string<InvalidArgumentException> $invalidArgumentExceptionClass
     */
    public static function setInvalidArgumentExceptionClass(string $invalidArgumentExceptionClass)
    {
        if (!is_a($invalidArgumentExceptionClass, InvalidArgumentException::class, true)) {
            throw new RuntimeException(
                'The invalid argument exception class must extend the InvalidArgumentException'
            );
        }

        self::$invalidArgumentExceptionClass = $invalidArgumentExceptionClass;
    }
}
