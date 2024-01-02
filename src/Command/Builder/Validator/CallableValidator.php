<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command\Builder\Validator;

use Ninja\Cosmic\Command\Builder\Validator\Exception\InvalidCallableException;
use ReflectionException;
use ReflectionMethod;

class CallableValidator
{
    /**
     * @throws ReflectionException
     * @throws InvalidCallableException
     */
    public static function validate(mixed $callable): void
    {
        if (self::isStaticCallToNonStaticMethod($callable)) {
            [$class, $method] = $callable;

            $message = "['{$class}', '{$method}'] is not a callable because '{$method}' is a static method.";
            $message .= " Either use [new {$class}(), '{$method}'] or configure a dependency injection container that supports auto wiring like PHP-DI."; //phpcs:ignore

            throw new InvalidCallableException($message);
        }
    }

    /**
     * @throws ReflectionException
     */
    private static function isStaticCallToNonStaticMethod(mixed $callable): bool
    {
        if (is_array($callable) && is_string($callable[0])) {
            [$class, $method] = $callable;
            $reflection       = new ReflectionMethod($class, $method);

            return !$reflection->isStatic();
        }

        return false;
    }

}
