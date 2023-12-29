<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Crypt;

use Exception;
use Ninja\Cosmic\Crypt\Parser\KeyParser;

/**
 * Class KeyRing
 *
 * A collection of cryptographic keys.
 */
class KeyRing
{
    /**
     * @var KeyCollection The collection of keys.
     */
    private KeyCollection $keys;

    /**
     * KeyRing constructor.
     *
     * @param string $type The type of keys in the keyring.
     */
    public function __construct(private readonly string $type = KeyInterface::GPG_TYPE_PUBLIC)
    {
        $this->keys = new KeyCollection([]);
    }

    /**
     * Create a new public keyring.
     *
     * @return self The new public keyring.
     * @throws Exception
     */
    public static function public(): self
    {
        $keyRing       = new self(KeyInterface::GPG_TYPE_PUBLIC);
        $keyRing->keys = (new KeyParser())->extractKeys(KeyInterface::GPG_TYPE_PUBLIC);
        return $keyRing;
    }

    /**
     * Create a new secret keyring.
     *
     * @return self The new secret keyring.
     * @throws Exception
     */
    public static function secret(): self
    {
        $keyRing       = new self(KeyInterface::GPG_TYPE_SECRET);
        $keyRing->keys = (new KeyParser())->extractKeys(KeyInterface::GPG_TYPE_SECRET);
        return $keyRing;
    }

    /**
     * Get the type of keys in the keyring.
     *
     * @return string The type of keys in the keyring.
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Get the number of keys in the keyring.
     *
     * @return int The number of keys in the keyring.
     */
    public function count(): int
    {
        return $this->keys->count();
    }

    /**
     * Check if the keyring is empty.
     *
     * @return bool True if the keyring is empty, false otherwise.
     */
    public function isEmpty(): bool
    {
        return $this->keys->isEmpty();
    }

    /**
     * Get all keys in the keyring.
     *
     * @return KeyCollection The collection of keys in the keyring.
     */
    public function all(): KeyCollection
    {
        return $this->keys;
    }

    /**
     * Add a key to the keyring.
     *
     * @param AbstractKey $key The key to add.
     */
    public function add(AbstractKey $key): void
    {
        $this->keys->add($key);
    }

    /**
     * Get a key from the keyring by its ID.
     * @throws Exception
     */
    public function get(string $id): ?AbstractKey
    {
        return $this->keys->getById($id);
    }

    /**
     * Check if the keyring contains a specific key.
     *
     * @param AbstractKey $key The key to check for.
     *
     * @return bool True if the keyring contains the key, false otherwise.
     */
    public function has(AbstractKey $key): bool
    {
        return $this->keys->contains($key);
    }
}
