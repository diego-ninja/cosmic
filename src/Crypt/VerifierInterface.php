<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Crypt;

interface VerifierInterface
{
    public static function verify(string $filePath): bool;
}
