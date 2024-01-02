<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Color\Exception;

use RuntimeException;

class ColorNotFoundException extends RuntimeException
{
    public static function withColor(string $color): self
    {
        return new self(sprintf('Color with name "%s" not found.', $color));
    }
}
