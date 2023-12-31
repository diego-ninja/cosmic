<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Color;

use Stringable;
use Ninja\Cosmic\Terminal\Theme\Element\AbstractThemeElement;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;

use function Termwind\style;

class Color extends AbstractThemeElement implements Stringable
{
    private ?Gradient $gradient = null;

    public function __construct(public string $name, public readonly string $color, public readonly bool $allowGradient)
    {
        parent::__construct($name);
    }

    public function getGradient(): ?Gradient
    {
        return $this->gradient;
    }

    public function setGradient(Gradient $gradient): void
    {
        $this->gradient = $gradient;
    }

    public function allowGradient(): bool
    {
        return $this->gradient instanceof Gradient;
    }

    /**
     * @param array<string, mixed> $input
     */
    public static function fromArray(array $input): Color
    {
        $color = new Color($input["name"], $input["color"], $input["allowGradient"] ?? false);
        if (isset($input["gradient"])) {
            $color->setGradient(Gradient::fromArray($input["gradient"]));
        }

        return $color;
    }

    public function load(OutputInterface $output): void
    {
        style($this->name)->color($this->color);
        $color = new OutputFormatterStyle($this->color);

        $output->getFormatter()->setStyle($this->name, $color);

        $this->gradient?->load($output);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            "name"     => $this->name,
            "color"    => $this->color,
            "gradient" => $this->gradient?->toArray(),
        ];
    }

    public function __toString(): string
    {
        return $this->color;
    }

    public function render(): string
    {
        $notGradient = sprintf("<%s>%s</>", $this->name, "▓▓▓▓▓▓▓▓▓");

        return
            sprintf(
                "<%s>%s</> %s <%s>%s</>",
                $this->name,
                "▓",
                $this->gradient?->render() ?? $notGradient,
                $this->name,
                $this->name
            );

    }

    public static function isAlias(string $name): bool
    {
        return str_starts_with($name, "@");
    }

    public static function isGradient(string $name): bool
    {
        return preg_match('/\d{3}$/', $name) === 1;
    }

}
