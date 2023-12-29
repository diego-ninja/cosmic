<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Charset;

use JsonException;
use Ninja\Cosmic\Terminal\Theme\Element\AbstractElementCollection;

/**
 * Class CharsetCollection
 *
 * @package Ninja\Cosmic\Terminal\Theme\Element\Charset
 * @extends AbstractElementCollection<Charset>
 */
class CharsetCollection extends AbstractElementCollection
{
    public function getType(): string
    {
        return Charset::class;
    }

    public function getCollectionType(): string
    {
        return self::class;
    }

    public function charset(string $name): ?Charset
    {
        foreach ($this->getIterator() as $item) {
            if ($item->name === $name) {
                /** @var Charset $item */
                return $item;
            }
        }

        return null;
    }

    /**
     * @throws JsonException
     */
    public static function fromFile(string $file): CharsetCollection
    {
        $collection = new CharsetCollection();

        $content = file_get_contents($file);
        if ($content === false) {
            return $collection;
        }

        $data       = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        foreach ($data["charsets"] as $name => $chars) {
            $collection->add(Charset::fromArray(["name" => $name, "chars" => $chars]));
        }

        return $collection;
    }

    /**
     * @param array<string, array<string, string>> $input
     * @return CharsetCollection
     */
    public static function fromArray(array $input): CharsetCollection
    {
        $collection = new CharsetCollection();
        foreach ($input as $name => $chars) {
            $collection->add(Charset::fromArray(["name" => $name, "chars" => $chars]));
        }

        return $collection;
    }
}
