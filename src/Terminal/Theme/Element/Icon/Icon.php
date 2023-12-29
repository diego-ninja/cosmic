<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Icon;

use Stringable;
use Ninja\Cosmic\Terminal\Theme\Element\AbstractThemeElement;

class Icon extends AbstractThemeElement implements Stringable
{
    public function __construct(public string $name, public readonly string $icon)
    {
        parent::__construct($name);
    }

    /**
     * Create an Icon instance from an array of data.
     *
     * @param array{
     *     name: string,
     *     icon: string,
     * } $input
     *
     * @return Icon
     */
    public static function fromArray(array $input): Icon
    {
        return new Icon($input["name"], $input["icon"]);
    }

    /**
     * Convert the Icon instance to an array.
     *
     * @return array{
     *     name: string,
     *     icon: string,
     * }
     */
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
