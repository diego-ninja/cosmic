<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Exception;

class ConfigFileNotFound extends CosmicException
{
    public static function forFile(string $file): self
    {
        return new self(sprintf('Config file %s not found', $file));
    }
}
