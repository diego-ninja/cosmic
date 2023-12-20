<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Spinner;

use Ninja\Cosmic\Terminal\Theme\Element\AbstractElementCollection;

class SpinnerCollection extends AbstractElementCollection
{
    public function getType(): string
    {
        return Spinner::class;
    }

    public function getCollectionType(): string
    {
        return __CLASS__;
    }

    public function spinner(string $name): ?Spinner
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
    public static function fromFile(string $file): SpinnerCollection
    {
        $collection = new SpinnerCollection();
        $data       = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
        foreach ($data["spinners"] as $name => $spinner) {
            $collection->add(Spinner::fromArray(["name" => $name, "frames" => $spinner["frames"], "interval" => $spinner["interval"]]));
        }

        return $collection;
    }

    public static function fromArray(array $input): SpinnerCollection
    {
        $collection = new SpinnerCollection();
        foreach ($input as $name => $spinner) {
            $collection->add(Spinner::fromArray(["name" => $name, "frames" => $spinner["frames"], "interval" => $spinner["interval"]]));
        }

        return $collection;
    }
}
