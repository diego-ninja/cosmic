<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Crypt;

/**
 * Interface CypherInterface
 *
 * Provides methods for encryption and decryption.
 */
interface CypherInterface
{
    /**
     * Encrypts the given data.
     *
     * @param string $data The data to be encrypted.
     *
     * @return string The encrypted data.
     */
    public function encrypt(string $data): string;

    /**
     * Decrypts the given data.
     *
     * @param string $data The data to be decrypted.
     *
     * @return string The decrypted data.
     */
    public function decrypt(string $data): string;
}
