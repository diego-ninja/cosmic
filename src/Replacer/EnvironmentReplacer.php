<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Replacer;

use Ninja\Cosmic\Config\Env;

class EnvironmentReplacer extends AbstractReplacer
{
    public const REPLACER_PREFIX = 'env';

    protected static ?EnvironmentReplacer $instance = null;

    public static function getInstance(): EnvironmentReplacer
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getPlaceholderValue(string $placeholder): string
    {
        if ($placeholder === "shell") {
            return Env::shell();
        }
        return (string)Env::get(strtoupper($placeholder));
    }
}
