<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Replacer\Exception;

class UndefinedReplacerPrefixException extends \RuntimeException
{
    public static function forReplacer(string $replacer): self
    {
        return new self(
            message: "Undefined replacer prefix for replacer: {$replacer}"
        );
    }
}
