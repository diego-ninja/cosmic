<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Crypt;

/**
 * @property string $type
 * @property string $id
 * @property string $createdAt
 * @property string $expiresAt
 * @property string $usage
 * @property string $fingerprint
 * @property string $method
 * @property Uid $uid
 */
interface KeyInterface
{
    public const GPG_TYPE_PUBLIC = 'pub';
    public const GPG_TYPE_SECRET = 'sec';
    public const GPG_TYPE_SUB    = 'sub';
    public const GPG_TYPE_SS     = 'ssb';

    public const GPG_PUB_REGEX_PATTERN = '/(?P<cypher>\w+)\/(?P<key_id>\w+)\s(?P<created_at>\d{4}-\d{2}-\d{2})\s\[(?P<usage>\w+)\](?:\s\[expires:\s(?P<expires_at>\d{4}-\d{2}-\d{2})\])?/';
    public const GPG_UID_REGEX_PATTERN = '/^uid\s+\[\s*(?P<trustLevel>.*?)\]\s*(?P<name>.*?)\s*<(?P<email>.*?)>$/';

    public const GPG_USAGE_SIGN    = 'S';
    public const GPG_USAGE_ENCRYPT = 'E';
    public const GPG_USAGE_AUTH    = 'A';
    public const GPG_USAGE_CERT    = 'C';

    public const GPG_TRUST_LEVEL_UNKNOWN  = 'unknown';
    public const GPG_TRUST_LEVEL_NEVER    = 'never';
    public const GPG_TRUST_LEVEL_MARGINAL = 'marginal';
    public const GPG_TRUST_LEVEL_FULLY    = 'fully';
    public const GPG_TRUST_LEVEL_ULTIMATE = 'ultimate';

    public function isAbleTo(string $usage): bool;
    public function isAbleToSign(): bool;
    public function __toString(): string;
    public static function fromArray(array $data): static;
    public static function fromString(string $string): static;
    public function addSubKey(KeyInterface $key): void;

}
