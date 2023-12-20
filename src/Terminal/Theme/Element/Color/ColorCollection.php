<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Color;

use Ninja\Cosmic\Terminal\Theme\Element\AbstractElementCollection;
use Symfony\Component\Console\Output\OutputInterface;

class ColorCollection extends AbstractElementCollection
{
    public function getType(): string
    {
        return Color::class;
    }

    public function getCollectionType(): string
    {
        return __CLASS__;
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
            $collection->add(Color::fromArray(["name" => $name, "color" => $color]));
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
     * @throws \JsonException
     */
    public static function fromFile(string $file): ColorCollection
    {
        $collection = new ColorCollection();
        $data       = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
        foreach ($data["colors"] as $name => $color) {
            $collection->add(Color::fromArray(["name" => $name, "color" => $color]));
        }

        return $collection;

    }

}
