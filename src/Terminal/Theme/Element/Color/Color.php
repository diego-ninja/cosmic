<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Color;

use Ninja\Cosmic\Terminal\Theme\Element\AbstractThemeElement;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;

use function Termwind\style;

class Color extends AbstractThemeElement
{
    public function __construct(public readonly string $name, public readonly string $color) {}

    public static function fromArray(array $input): Color
    {
        return new Color($input["name"], $input["color"]);
    }

    public function load(OutputInterface $output): void
    {
        style($this->name)->color($this->color);
        $color = new OutputFormatterStyle($this->color);

        $output->getFormatter()->setStyle($this->name, $color);
    }

    public function toArray(): array
    {
        return [
            "name"  => $this->name,
            "color" => $this->color,
        ];
    }

    public function __toString(): string
    {
        return $this->color;
    }
}
