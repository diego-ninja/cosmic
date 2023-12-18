<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Application\Publisher\Asset;

use JsonException;
use Ninja\Cosmic\Serializer\SerializableInterface;
use Ramsey\Collection\AbstractCollection;

class AssetCollection extends AbstractCollection implements SerializableInterface
{
    public function getType(): string
    {
        return Asset::class;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->jsonSerialize(), JSON_THROW_ON_ERROR);
    }
}
