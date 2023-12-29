<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Crypt;

use Exception;
use JsonException;
use Ninja\Cosmic\Serializer\SerializableInterface;
use Ramsey\Collection\AbstractCollection;

/**
 * Class KeyCollection
 *
 * A collection of AbstractKey objects.
 * This class is used to store the keys retrieved from the key servers.
 *
 * @package Ninja\Cosmic\Crypt
 * @extends AbstractCollection<AbstractKey>
 * @implements SerializableInterface<AbstractKey>
 */
class KeyCollection extends AbstractCollection implements SerializableInterface
{
    /**
     * Get the type of elements in the collection.
     *
     * @return string The type of elements in the collection.
     */
    public function getType(): string
    {
        return AbstractKey::class;
    }

    /**
     * Get an AbstractKey object by its ID.
     *
     * @param string $id The ID of the AbstractKey object.
     *
     * @return AbstractKey|null The AbstractKey object if found, null otherwise.
     *
     * @throws Exception If an error occurs.
     */
    public function getById(string $id): ?AbstractKey
    {
        foreach ($this->getIterator() as $item) {
            if ($item->id === $id) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Get an AbstractKey object(s) by the associated email.
     *
     * @param string $email The email associated with the AbstractKey object(s).
     *
     * @return array<AbstractKey>|AbstractKey The AbstractKey object(s) if found.
     *
     * @throws Exception If an error occurs.
     */
    public function getByEmail(string $email): array | AbstractKey
    {
        $keys = [];
        foreach ($this->getIterator() as $item) {
            if ($item->uid?->email === $email) {
                $keys[] = $item;
            }
        }

        return count($keys) === 1 ? $keys[0] : $keys;
    }

    /**
     * Convert the collection to a JSON string.
     *
     * @return string The JSON string representation of the collection.
     *
     * @throws JsonException If an error occurs during JSON encoding.
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array<string,mixed> The data to be serialized to JSON.
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
