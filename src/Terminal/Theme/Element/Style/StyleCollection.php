<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Style;

use Ninja\Cosmic\Terminal\Theme\Element\AbstractElementCollection;
use Symfony\Component\Console\Output\OutputInterface;

class StyleCollection extends AbstractElementCollection
{
    public function getType(): string
    {
        return AbstractStyle::class;
    }

    public function load(OutputInterface $output): void
    {
        foreach ($this->getIterator() as $item) {
            $item->load($output);
        }
    }

    public function getCollectionType(): string
    {
        return __CLASS__;
    }

    public function style(string $name): ?AbstractStyle
    {
        foreach ($this->getIterator() as $item) {
            if ($item->name === $name) {
                return $item;
            }
        }

        return null;
    }

    public static function fromFile(string $file): StyleCollection
    {
        $collection = new StyleCollection();
        $data       = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
        foreach ($data["styles"] as $substyle => $styles) {
            foreach ($styles as $name => $style) {
                $style = $substyle === AbstractStyle::TERMWIND_STYLE ?
                    TermwindStyle::fromArray(["name" => $name, "style" => $style]) :
                    SymfonyStyle::fromArray([
                        "name"    => $name,
                        "fg"      => $style["fg"]      ?? null,
                        "bg"      => $style["bg"]      ?? null,
                        "options" => $style["options"] ?? [],
                    ]);

                $collection->add($style);
            }

        }

        return $collection;
    }

    public static function fromArray(array $data): StyleCollection
    {
        $collection = new StyleCollection();
        foreach ($data as $substyle => $styles) {
            foreach ($styles as $name => $style) {
                $style = $substyle === AbstractStyle::TERMWIND_STYLE ?
                    TermwindStyle::fromArray(["name" => $name, "style" => $style]) :
                    SymfonyStyle::fromArray([
                        "name"    => $name,
                        "fg"      => $style["fg"]      ?? null,
                        "bg"      => $style["bg"]      ?? null,
                        "options" => $style["options"] ?? [],
                    ]);

                $collection->add($style);
            }

        }

        return $collection;
    }

    public function toArray(): array
    {
        $elements = parent::toArray();
        $output   = [];
        foreach ($elements as $element) {
            if ($element instanceof TermwindStyle) {
                $output[$element->name] = $element->style;
            } elseif ($element instanceof SymfonyStyle) {
                $output[$element->name] = [
                    "fg"      => $element->fg,
                    "bg"      => $element->bg,
                    "options" => $element->options,
                ];
            }
        }

        return $output;
    }

    public function termwind(string $name): ?TermwindStyle
    {
        foreach ($this->getIterator() as $item) {
            if ($item instanceof TermwindStyle && $item->name === $name) {
                return $item;
            }
        }

        return null;
    }

    public function symfony(string $name): ?SymfonyStyle
    {
        foreach ($this->getIterator() as $item) {
            if ($item instanceof SymfonyStyle && $item->name === $name) {
                return $item;
            }
        }

        return null;
    }

}
