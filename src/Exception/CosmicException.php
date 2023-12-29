<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Exception;

use Exception;
use Throwable;

/** @phpstan-consistent-constructor */
class CosmicException extends Exception
{
    public static function fromException(Throwable $ex): self
    {
        return new self(
            message: $ex->getMessage(),
            code: $ex->getCode(),
            previous: $ex
        );
    }

}
