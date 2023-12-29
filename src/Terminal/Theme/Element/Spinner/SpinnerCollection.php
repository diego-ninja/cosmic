<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Spinner;

use JsonException;
use Ninja\Cosmic\Terminal\Theme\Element\AbstractElementCollection;

/**
 * Class SpinnerCollection
 *
 * @package Ninja\Cosmic\Terminal\Theme\Element\Spinner
 * @extends AbstractElementCollection<Spinner>
 */
class SpinnerCollection extends AbstractElementCollection
{
    public function getType(): string
    {
        return Spinner::class;
    }

    public function getCollectionType(): string
    {
        return self::class;
    }

    public function spinner(string $name): ?Spinner
    {
        foreach ($this->getIterator() as $item) {
            if ($item->name === $name) {
                /** @var Spinner $item */
                return $item;
            }
        }

        return null;
    }

    /**
     * @throws JsonException
     */
    public static function fromFile(string $file): SpinnerCollection
    {
        $collection = new SpinnerCollection();

        $content = file_get_contents($file);
        if ($content === false) {
            return $collection;
        }

        $data       = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        foreach ($data["spinners"] as $name => $spinner) {
            $collection->add(Spinner::fromArray(["name" => $name, "frames" => $spinner["frames"], "interval" => $spinner["interval"]]));
        }

        return $collection;
    }

    /**
     * @param array<string, array{
     *     interval: int,
     *     frames: array<int, string>,
     * }> $input
     * @return SpinnerCollection
     */
    public static function fromArray(array $input): SpinnerCollection
    {
        $collection = new SpinnerCollection();
        foreach ($input as $name => $spinner) {
            $collection->add(
                Spinner::fromArray([
                    "name" => $name,
                    "frames" => $spinner["frames"],
                    "interval" => $spinner["interval"]
                ])
            );
        }

        return $collection;
    }
}
