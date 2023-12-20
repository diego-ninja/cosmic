<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Icon;

use Ninja\Cosmic\Terminal\Theme\Element\AbstractElementCollection;

class IconCollection extends AbstractElementCollection
{
    public function getType(): string
    {
        return Icon::class;
    }

    public function getCollectionType(): string
    {
        return __CLASS__;
    }

    public function icon(string $name): ?Icon
    {
        foreach ($this->getIterator() as $item) {
            if ($item->name === $name) {
                return $item;
            }
        }

        return null;
    }

    /**
     * @throws \JsonException
     */
    public static function fromFile(string $file): IconCollection
    {
        $collection = new IconCollection();
        $data       = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
        foreach ($data["icons"] as $name => $icon) {
            $collection->add(Icon::fromArray(["name" => $name, "icon" => $icon]));
        }

        return $collection;
    }

    public static function fromArray(array $input): IconCollection
    {
        $collection = new IconCollection();
        foreach ($input as $name => $icon) {
            $collection->add(Icon::fromArray(["name" => $name, "icon" => $icon]));
        }

        return $collection;
    }
}
