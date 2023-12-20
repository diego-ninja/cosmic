<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Icon;

use Ninja\Cosmic\Terminal\Theme\Element\AbstractThemeElement;

class Icon extends AbstractThemeElement
{
    public function __construct(public readonly string $name, public readonly string $icon) {}

    public static function fromArray(array $input): Icon
    {
        return new Icon($input["name"], $input["icon"]);
    }

    public function toArray(): array
    {
        return [
            "name" => $this->name,
            "icon" => $this->icon,
        ];
    }

    public function __toString(): string
    {
        return $this->icon;
    }
}
