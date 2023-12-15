<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Signer;

interface SignerInterface
{
    public const SIGNATURE_SUFFIX      = "asc";
    public const PGP_KEY_REGEX_PATTERN = '/(?P<cypher>\w+)\/(?P<key_id>\w+)\s(?P<created_at>\d{4}-\d{2}-\d{2})\s\[(?P<usage>\w+)\]\s\[expires:\s(?P<expires_at>\d{4}-\d{2}-\d{2})\]/';

    public function sign(): bool;
    public function verify(): bool;
}
