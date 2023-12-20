<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Charset;

use Ninja\Cosmic\Terminal\Theme\Element\AbstractElementCollection;

class CharsetCollection extends AbstractElementCollection
{
    public function getType(): string
    {
        return Charset::class;
    }

    public function getCollectionType(): string
    {
        return __CLASS__;
    }

    public function charset(string $name): ?Charset
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
    public static function fromFile(string $file): CharsetCollection
    {
        $collection = new CharsetCollection();
        $data       = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
        foreach ($data["charsets"] as $name => $chars) {
            $collection->add(Charset::fromArray(["name" => $name, "chars" => $chars]));
        }

        return $collection;
    }

    public static function fromArray(array $input): CharsetCollection
    {
        $collection = new CharsetCollection();
        foreach ($input as $name => $chars) {
            $collection->add(Charset::fromArray(["name" => $name, "chars" => $chars]));
        }

        return $collection;
    }
}
