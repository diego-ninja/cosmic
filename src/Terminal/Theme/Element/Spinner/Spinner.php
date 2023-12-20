<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Spinner;

use Ninja\Cosmic\Terminal\Theme\Element\AbstractThemeElement;

class Spinner extends AbstractThemeElement
{
    public function __construct(public readonly string $name, public readonly array $frames, public readonly int $interval) {}

    public static function fromArray(array $input): Spinner
    {
        return new Spinner(
            name: $input["name"],
            frames: $input["frames"],
            interval: $input["interval"]
        );
    }
}
