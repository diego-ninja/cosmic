<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Exception;

class UnexpectedValueException extends CosmicException
{
    public static function fromValue(mixed $value): self
    {
        $type = get_debug_type($value);
        return new self(\sprintf('Unexpected value of type "%s" with value %s', $type, $value));
    }
}
