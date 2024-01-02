<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Input\Select\Input\Exception;

use RuntimeException;

class UnknownOptionException extends RuntimeException
{
    public static function withOption(string $option): self
    {
        return new self(sprintf('Unknown option: %s', $option));
    }
}
