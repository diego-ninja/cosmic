<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Replacer;

use Ninja\Cosmic\Config\Env;

class EnvironmentReplacer extends AbstractReplacer
{
    public const REPLACER_PREFIX = 'env';

    public function getPlaceholderValue(string $placeholder): mixed
    {
        if ($placeholder === "shell") {
            return Env::shell();
        }
        return Env::get(strtoupper($placeholder));
    }
}
