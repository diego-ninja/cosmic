<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Exception;

use Ninja\Cosmic\Serializer\SerializableInterface;

class MissingInterfaceException extends CosmicException
{
    public static function withInterface(string $class, string $interface): self
    {
        return new self(message: sprintf(
            'Class %s must implement %s',
            $class,
            $interface
        ));
    }
}
