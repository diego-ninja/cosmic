<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Exception;

use Ninja\Cosmic\Exception\CosmicException;

class ThemeNotFoundException extends CosmicException
{
    public static function fromThemeName(string $themeName): self
    {
        return new self("Theme with name '$themeName' not found.");
    }
}
