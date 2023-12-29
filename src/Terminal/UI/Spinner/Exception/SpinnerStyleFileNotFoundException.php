<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Spinner\Exception;

use RuntimeException;
class SpinnerStyleFileNotFoundException extends RuntimeException
{
    public static function withFile(string $file): self
    {
        return new self(sprintf('Spinner styles definitions file %s not found', $file));
    }
}
