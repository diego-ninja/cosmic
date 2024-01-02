<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Serializer;

use JsonSerializable;

/**
 * Interface SerializableInterface
 * @template T
 */
interface SerializableInterface extends JsonSerializable
{
    /**
     * @return array<string,mixed>|null
     */
    public function toArray(): ?array;
    public function toJson(): string;
}
