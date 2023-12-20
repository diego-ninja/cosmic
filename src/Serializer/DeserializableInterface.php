<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Serializer;

interface DeserializableInterface
{
    public static function fromArray(array $data): static;
    public static function fromJson(string $json): static;
}
