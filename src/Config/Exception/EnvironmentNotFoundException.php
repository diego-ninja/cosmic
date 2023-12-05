<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Config\Exception;

class EnvironmentNotFoundException extends \RuntimeException
{
    public static function forEnv(string $env_file): self
    {
        return new self(
            message: sprintf(
                'Environment file "%s" not found.',
                $env_file
            )
        );
    }
}
