<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Color;

use Ninja\Cosmic\Terminal\Theme\Element\AbstractThemeElement;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;

use function Cosmic\gradient;
use function Termwind\style;

class Color extends AbstractThemeElement
{
    private ?Gradient $gradient = null;

    public function __construct(public readonly string $name, public readonly string $color) {}

    public function getGradient(): ?Gradient
    {
        return $this->gradient;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setGradient(Gradient $gradient): void
    {
        $this->gradient = $gradient;
    }

    public static function fromArray(array $input): Color
    {
        return new Color($input["name"], $input["color"]);
    }

    public function load(OutputInterface $output): void
    {
        style($this->name)->color($this->color);
        $color = new OutputFormatterStyle($this->color);

        $output->getFormatter()->setStyle($this->name, $color);

        $this->gradient?->load($output);
    }

    public function toArray(): array
    {
        return [
            "name"  => $this->name,
            "color" => $this->color,
            "gradient" => $this->gradient->toArray()
        ];
    }

    public function __toString(): string
    {
        return $this->color;
    }

    public function render(): string
    {
        return
            sprintf(
                "Color: <%s>%s</> <%s>%s</> - Variations: %s",
                $this->name,
                $this->name,
                $this->name,
                "▓",
                $this->gradient->render()
            );
    }
}
