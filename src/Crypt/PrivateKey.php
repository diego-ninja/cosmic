<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Crypt;

final class PrivateKey extends AbstractKey implements CypherInterface
{
    public function encrypt(string $data): string
    {
        return $data;
    }

    public function decrypt(string $data): string
    {
        return $data;
    }
}
