<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Input\Select\Input\Exception;

class IndexOutOfRangeException extends \RuntimeException
{
    public static function withIndex(string $index): self
    {
        return new self(sprintf('Index out of range: %d', $index));
    }
}
