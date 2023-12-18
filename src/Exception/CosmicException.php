<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Exception;

use Throwable;

/** @phpstan-consistent-constructor */
class CosmicException extends \Exception
{
    public static function fromException(Throwable $ex): static
    {
        return new static(
            message: $ex->getMessage(),
            code: $ex->getCode(),
            previous: $ex
        );
    }

}
