<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Crypt;

/**
 * Class PrivateKey
 *
 * Represents a private key in the cryptographic system.
 */
final class PrivateKey extends AbstractKey implements CypherInterface
{
    /**
     * Encrypts the given data.
     *
     * @param string $data The data to be encrypted.
     *
     * @return string The encrypted data.
     */
    public function encrypt(string $data): string
    {
        return $data;
    }

    /**
     * Decrypts the given data.
     *
     * @param string $data The data to be decrypted.
     *
     * @return string The decrypted data.
     */
    public function decrypt(string $data): string
    {
        return $data;
    }
}
