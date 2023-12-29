<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Crypt;

/**
 * Interface KeyInterface
 *
 * Provides methods for handling cryptographic keys.
 *
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

    /**
     * Check if the key is able to perform a specific usage.
     *
     * @param string $usage The usage to check.
     *
     * @return bool True if the key is able to perform the usage, false otherwise.
     */
    public function isAbleTo(string $usage): bool;

    /**
     * Check if the key is able to sign.
     *
     * @return bool True if the key is able to sign, false otherwise.
     */
    public function isAbleToSign(): bool;

    /**
     * Get the string representation of the key.
     *
     * @return string The string representation of the key.
     */
    public function __toString(): string;

    /**
     * Create a new instance of the key from an array of data.
     *
     * @param array<string,mixed> $data The data to create the key from.
     *
     * @return static The new instance of the key.
     */
    public static function fromArray(array $data): static;

    /**
     * Create a new instance of the key from a string.
     *
     * @param string $string The string to create the key from.
     *
     * @return static The new instance of the key.
     */
    public static function fromString(string $string): static;

    /**
     * Add a subkey to the key.
     */
    public function addSubKey(AbstractKey $key): void;
}
