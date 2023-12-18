<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Application\Publisher\Asset;

use Ninja\Cosmic\Crypt\Verifier;
use Ninja\Cosmic\Environment\Env;

class Signature extends Asset
{
    public function __construct(private readonly string $signed_file)
    {
        $name = sprintf('%s GPG Signature', ucfirst(Env::get('APP_NAME')));
        $path = sprintf('%s.asc', $signed_file);
        parent::__construct($name, $path);
    }

    public static function for(string $file): self
    {
        return new self($file);
    }

    public function verify(): bool
    {
        return Verifier::verify($this->signed_file);
    }
}
