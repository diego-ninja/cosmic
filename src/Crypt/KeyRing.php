<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Crypt;

use Ninja\Cosmic\Crypt\Parser\KeyParser;

class KeyRing
{
    private KeyCollection $keys;

    public function __construct(private string $type = KeyInterface::GPG_TYPE_PUBLIC)
    {
        $this->keys = new KeyCollection([]);
    }

    public static function public(): self
    {
        $keyRing       = new self(KeyInterface::GPG_TYPE_PUBLIC);
        $keyRing->keys = (new KeyParser())->extractKeys(KeyInterface::GPG_TYPE_PUBLIC);
        return $keyRing;
    }

    public static function secret(): self
    {
        $keyRing       = new self(KeyInterface::GPG_TYPE_SECRET);
        $keyRing->keys = (new KeyParser())->extractKeys(KeyInterface::GPG_TYPE_SECRET);
        return $keyRing;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function count(): int
    {
        return $this->keys->count();
    }

    public function isEmpty(): bool
    {
        return $this->keys->isEmpty();
    }

    public function all(): KeyCollection
    {
        return $this->keys;
    }

    public function add(KeyInterface $key): void
    {
        $this->keys->add($key);
    }

    public function get(string $id): KeyInterface
    {
        return $this->keys->getById($id);
    }

    public function has(KeyInterface $key): bool
    {
        return $this->keys->contains($key);
    }
}
