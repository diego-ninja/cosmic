<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Signer\Exception;

class SignatureFileNotFoundException extends \RuntimeException
{
    public static function for(string $file): self
    {
        return new self(sprintf('Signature file not found for %s', $file));
    }

}
