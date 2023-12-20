<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Serializer;

use DateTimeInterface;
use JsonException;
use Ramsey\Uuid\UuidInterface;

trait SerializableTrait
{
    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this, JSON_THROW_ON_ERROR);
    }

    public function toArray(): ?array
    {
        $clone = (array)$this;
        $ret   = [];

        foreach ($clone as $key => $value) {
            $aux    = explode("\0", $key);
            $newKey = $aux[count($aux) - 1];

            if ($value instanceof DateTimeInterface) {
                $ret[$newKey] = $value->format("Y-m-d\TH:i:sP");
            } elseif ($value instanceof UuidInterface) {
                $ret[$newKey] = $value->toString();
            } elseif (is_float($value)) {
                if (defined(static::class . "::PRECISION")) {
                    $precision = static::PRECISION;
                } else {
                    $precision = 2;
                }
                // Round floats to 2 decimal points by default
                $ret[$newKey] = round($value, $precision);
            } else {
                $ret[$newKey] = $value instanceof SerializableInterface ? $value->toArray() : $value;
            }
        }

        return $ret;
    }

    public function jsonSerialize(): ?array
    {
        return $this->toArray();
    }

}
