<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Application\Publisher\Asset;

use Ninja\Cosmic\Crypt\Verifier;
use Ninja\Cosmic\Environment\Env;

/**
 * Class Signature
 *
 * @package Ninja\Cosmic\Application
 */
class Signature extends Asset
{
    /**
     * Signature constructor.
     *
     * @param string $signed_file The file that is signed.
     */
    public function __construct(private readonly string $signed_file)
    {
        $name = sprintf('%s GPG Signature', ucfirst((string)Env::get('APP_NAME')));
        $path = sprintf('%s.asc', $signed_file);
        parent::__construct($name, $path);
    }

    /**
     * Create a Signature instance for the specified file.
     *
     * @param string $file The file for which to create a Signature instance.
     */
    public static function for(string $file): self
    {
        return new self($file);
    }

    /**
     * Verify the file using the guessed signature.
     */
    public function verify(): bool
    {
        return Verifier::verify($this->signed_file);
    }
}
