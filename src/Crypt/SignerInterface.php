<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Crypt;

interface SignerInterface
{
    public const SIGNATURE_SUFFIX = "asc";
    public function sign(string $file_path): bool;
}
