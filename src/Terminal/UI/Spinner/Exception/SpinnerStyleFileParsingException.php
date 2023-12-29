<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Spinner\Exception;

use RuntimeException;
use Throwable;
class SpinnerStyleFileParsingException extends RuntimeException
{
    public static function withFile(string $file, ?Throwable $previous): self
    {
        return new self(
            message: sprintf('Spinner styles definitions file %s could not be parsed', $file),
            previous: $previous
        );
    }
}
