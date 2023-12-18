<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Crypt;

use Exception;
use JsonException;
use Ninja\Cosmic\Serializer\SerializableInterface;
use Ramsey\Collection\AbstractCollection;

class KeyCollection extends AbstractCollection implements SerializableInterface
{
    public function getType(): string
    {
        return AbstractKey::class;
    }

    /**
     * @throws Exception
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

    public function getByEmail(string $email): array | AbstractKey
    {
        $keys = [];
        foreach ($this->getIterator() as $item) {
            if ($item->uid->email === $email) {
                $keys[] = $item;
            }
        }

        return count($keys) === 1 ? $keys[0] : $keys;
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
