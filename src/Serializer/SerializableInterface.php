<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Serializer;

interface SerializableInterface extends \JsonSerializable
{
    public function toArray(): ?array;
    public function toJson(): string;
}
