<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Serializer;

interface DeserializableInterface
{
    public function fromArray(array $data): void;
    public function fromJson(string $json): void;
}
