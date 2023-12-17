<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Crypt;

interface CypherInterface
{
    public function encrypt(string $data): string;
    public function decrypt(string $data): string;
}
