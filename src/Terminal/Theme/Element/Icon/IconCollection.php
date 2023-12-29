<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Icon;

use JsonException;
use Ninja\Cosmic\Terminal\Theme\Element\AbstractElementCollection;

/**
 * Class IconCollection
 *
 * @package Ninja\Cosmic\Terminal\Theme\Element\Icon
 * @extends AbstractElementCollection<Icon>
 */
class IconCollection extends AbstractElementCollection
{
    public function getType(): string
    {
        return Icon::class;
    }

    public function getCollectionType(): string
    {
        return self::class;
    }

    public function icon(string $name): ?Icon
    {
        foreach ($this->getIterator() as $item) {
            if ($item->name === $name) {
                /** @var Icon $item */
                return $item;
            }
        }

        return null;
    }

    /**
     * @throws JsonException
     */
    public static function fromFile(string $file): IconCollection
    {
        $collection = new IconCollection();

        $content = file_get_contents($file);
        if ($content === false) {
            return $collection;
        }

        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        foreach ($data["icons"] as $name => $icon) {
            $collection->add(Icon::fromArray(["name" => $name, "icon" => $icon]));
        }

        return $collection;
    }

    /**
     * @param array<string, string> $input
     * @return IconCollection
     */
    public static function fromArray(array $input): IconCollection
    {
        $collection = new IconCollection();
        foreach ($input as $name => $icon) {
            $collection->add(Icon::fromArray(["name" => $name, "icon" => $icon]));
        }

        return $collection;
    }
}
