<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Color\Exception;

class GradientNotSupportedException extends \RuntimeException
{
    public static function whithColor(string $color): self
    {
        return new self(sprintf('Color "%s" does not support gradients.', $color));
    }
}
