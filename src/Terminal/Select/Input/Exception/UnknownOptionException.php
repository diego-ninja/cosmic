<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Select\Input\Exception;

class UnknownOptionException extends \RuntimeException
{
    public static function withOption(string $option): self
    {
        return new self(sprintf('Unknown option: %s', $option));
    }
}
