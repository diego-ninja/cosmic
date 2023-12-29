<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Color;

use Ninja\Cosmic\Terminal\Theme\Element\AbstractThemeElement;

use Symfony\Component\Console\Output\OutputInterface;

use function Cosmic\gradient;

class Gradient extends AbstractThemeElement
{
    final public const GRADIENT_DEFAULT_VARIATIONS = 10;
    final public const GRADIENT_DEFAULT_DEVIATION  = 2;

    public function __construct(public string $name, public readonly ColorCollection $colors)
    {
        parent::__construct($name);
    }

    public static function withSeed(Color $color): Gradient
    {
        $colors = new ColorCollection();

        $gradient           = array_unique(array_merge(self::light($color), self::dark($color)));
        $starting_variation = 100;
        foreach ($gradient as $variation) {
            $colors->add(new Color(sprintf("%s%d", $color->name, $starting_variation), $variation, false));
            $starting_variation += 100;
        }

        return new Gradient($color->name, $colors);

    }

    /**
     * @param array<string, mixed> $input
     */
    public static function fromArray(array $input): Gradient
    {
        $colors = new ColorCollection();
        foreach ($input["colors"] as $color) {
            $colors->add(Color::fromArray($color));
        }

        return new Gradient($input["name"], $colors);
    }

    public function load(OutputInterface $output): void
    {
        foreach ($this->colors as $color) {
            /** @var Color $color */
            $color->load($output);
        }
    }

    /**
     * @return array<string>
     */
    private static function light(Color $color): array
    {
        $gradient_variations = (self::GRADIENT_DEFAULT_VARIATIONS / 2) + self::GRADIENT_DEFAULT_DEVIATION;
        $light_gradient      = gradient("#ffffff", $color->color, $gradient_variations);

        for($i = 0; $i < self::GRADIENT_DEFAULT_DEVIATION; $i++) {
            array_shift($light_gradient);
        }

        return $light_gradient;
    }

    /**
     * @return array<string>
     */
    private static function dark(Color $color): array
    {
        $gradient_variations = (self::GRADIENT_DEFAULT_VARIATIONS / 2) + self::GRADIENT_DEFAULT_DEVIATION;
        $dark_gradient       = gradient($color->color, "#000000", $gradient_variations);

        for($i = 0; $i < self::GRADIENT_DEFAULT_DEVIATION; $i++) {
            array_pop($dark_gradient);
        }

        return $dark_gradient;
    }

    public function render(): string
    {
        $gradient = "";
        foreach ($this->colors as $color) {
            $gradient .= sprintf("<%s>%s</>", $color->name, "▓");
        }

        return $gradient;
    }
}
