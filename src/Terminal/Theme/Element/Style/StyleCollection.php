<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Style;

use JsonException;
use Ninja\Cosmic\Terminal\Theme\Element\AbstractElementCollection;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StyleCollection
 *
 * @package Ninja\Cosmic\Terminal\Theme\Element\Style
 * @extends AbstractElementCollection<AbstractStyle>
 */
class StyleCollection extends AbstractElementCollection
{
    public function getType(): string
    {
        return AbstractStyle::class;
    }

    public function load(OutputInterface $output): void
    {
        foreach ($this->getIterator() as $item) {
            /** @var AbstractStyle $item */
            $item->load($output);
        }
    }

    public function getCollectionType(): string
    {
        return self::class;
    }

    public function style(string $name): ?AbstractStyle
    {
        foreach ($this->getIterator() as $item) {
            if ($item->name === $name) {
                /** @var AbstractStyle $item */
                return $item;
            }
        }

        return null;
    }

    /**
     * @throws JsonException
     */
    public static function fromFile(string $file): StyleCollection
    {
        $collection = new StyleCollection();
        $content    = file_get_contents($file);
        if ($content === false) {
            return $collection;
        }

        $data       = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
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

    /**
     * @param array<string, array<string, mixed>> $data
     * @return StyleCollection
     */
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

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $elements = parent::toArray();
        $output   = [];
        foreach ($elements as $element) {
            if ($element instanceof TermwindStyle) {
                $output[$element->name] = $element->style;
            } elseif ($element instanceof SymfonyStyle) {
                $output[$element->name] = [
                    "fg"      => $element->foreground,
                    "bg"      => $element->background,
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
