<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Exception;

use function get_class;
use function gettype;
use function is_object;

class UnexpectedValueException extends CosmicException
{
    public static function fromValue(mixed $value): self
    {
        $type = is_object($value) ? get_class($value) : gettype($value);
        return new self(\sprintf('Unexpected value of type "%s" with value %s', $type, $value));
    }
}
