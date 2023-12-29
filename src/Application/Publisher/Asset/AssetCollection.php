<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Application\Publisher\Asset;

use JsonException;
use Ninja\Cosmic\Serializer\SerializableInterface;
use Ramsey\Collection\AbstractCollection;

/**
 * Class AssetCollection
 *
 * @package Ninja\Cosmic\Application
 *
 * @template T
 * @extends AbstractCollection<Asset>
 * @implements SerializableInterface<T>
 */
class AssetCollection extends AbstractCollection implements SerializableInterface
{
    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return Asset::class;
    }

    /**
     * @inheritDoc
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Return a JSON representation of the collection.
     *
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->jsonSerialize(), JSON_THROW_ON_ERROR);
    }
}
