<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Replacer;

use Ninja\Cosmic\Environment\Env;
use Ninja\Cosmic\Exception\BinaryNotFoundException;

class EnvironmentReplacer extends AbstractReplacer
{
    final public const REPLACER_PREFIX = 'env';

    protected static ?EnvironmentReplacer $instance = null;

    public static function getInstance(): EnvironmentReplacer
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @throws BinaryNotFoundException
     */
    public function getPlaceholderValue(string $placeholder): string
    {
        if ($placeholder === "shell") {
            return Env::shell();
        }

        if ($placeholder === "app_version") {
            return Env::appVersion();
        }

        return (string)Env::get(strtoupper($placeholder));
    }
}
