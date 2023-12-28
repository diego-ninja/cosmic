<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Color;

use JsonException;
use Ninja\Cosmic\Terminal\Theme\Element\AbstractElementCollection;
use Ninja\Cosmic\Terminal\Theme\Element\Color\Exception\ColorNotFoundException;
use Ninja\Cosmic\Terminal\Theme\Element\Color\Exception\GradientNotSupportedException;
use Symfony\Component\Console\Output\OutputInterface;

class ColorCollection extends AbstractElementCollection
{
    private const GRADIENT_EXCLUDED = ["black", "white"];

    public function getType(): string
    {
        return Color::class;
    }

    public function getCollectionType(): string
    {
        return __CLASS__;
    }

    public function getByName(string $name): ?Color
    {
        foreach ($this->getIterator() as $item) {
            if ($item->name === $name) {
                return $item;
            }
        }

        return null;
    }

    public function toArray(): array
    {
        $elements = parent::toArray();
        $output   = [];
        foreach ($elements as $element) {
            $output[$element->name] = $element->color;
        }

        return $output;
    }

    public static function fromArray(array $input): ColorCollection
    {
        $collection = new ColorCollection();
        foreach ($input as $name => $color) {
            $color = Color::fromArray(["name" => $name, "color" => self::resolveColor($color, $collection)]);
            if (!in_array($color->name, self::GRADIENT_EXCLUDED, true)) {
                $color->setGradient(Gradient::withSeed($color));
            }

            $collection->add($color);
        }

        return $collection;
    }

    public function color(string $name): ?Color
    {
        foreach ($this->getIterator() as $item) {
            if ($item->name === $name) {
                return $item;
            }
        }

        return null;

    }

    public function load(OutputInterface $output): void
    {
        foreach ($this->getIterator() as $item) {
            $item->load($output);
        }
    }

    /**
     * @throws JsonException
     */
    public static function fromFile(string $file): ColorCollection
    {
        $collection = new ColorCollection();
        $data       = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
        foreach ($data["colors"] as $name => $color) {
            $color = Color::fromArray(["name" => $name, "color" => self::resolveColor($color, $collection)]);
            if (!in_array($color->name, self::GRADIENT_EXCLUDED, true)) {
                $color->setGradient(Gradient::withSeed($color));
            }

            $collection->add($color);
        }

        return $collection;

    }

    /**
     * Resolve color aliases and gradients
     *
     * @throws ColorNotFoundException
     * @throws GradientNotSupportedException
     */
    public static function resolveColor(string $color, ColorCollection $collection): ?string
    {
        if (Color::isAlias($color)) {
            if (Color::isGradient($color)) {
                $gradient_color = substr($color, 1);
                $seed_color     = substr($gradient_color, 0, -3);

                $seed = $collection->getByName($seed_color);

                if (!$seed) {
                    throw ColorNotFoundException::withColor($seed_color);
                }

                if ($seed->allowGradient() === false) {
                    throw GradientNotSupportedException::whithColor($seed_color);
                }

                $color = $seed->getGradient()->colors->getByName($gradient_color)->color;
            } else {
                $color = $collection->getByName(substr($color, 1))->color;
            }
        }

        return $color;
    }
}
