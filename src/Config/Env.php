<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Config;

use Dotenv\Repository\Adapter\PutenvAdapter;
use Dotenv\Repository\RepositoryBuilder;
use Dotenv\Repository\RepositoryInterface;
use Phar;
use PhpOption\Option;

class Env
{
    protected static bool $putenv = true;

    protected static ?RepositoryInterface $repository = null;

    public static function enablePutenv(): void
    {
        static::$putenv     = true;
        static::$repository = null;
    }

    public static function disablePutenv(): void
    {
        static::$putenv     = false;
        static::$repository = null;
    }

    public static function getRepository(): RepositoryInterface
    {
        if (static::$repository === null) {
            $builder = RepositoryBuilder::createWithDefaultAdapters();

            if (static::$putenv) {
                $builder = $builder->addAdapter(PutenvAdapter::class);
            }

            static::$repository = $builder->immutable()->make();
        }

        return static::$repository;
    }

    public static function basePath(?string $dir = null): ?string
    {
        $base_path = is_phar() ? Phar::running() : self::get("BASE_PATH", getcwd());
        return $dir ? sprintf("%s/%s", $base_path, $dir) : $base_path;
    }

    public static function buildPath(?string $dir = null): ?string
    {
        $default    = self::basePath("builds");
        $build_path = is_phar() ? $default : self::get("BUILD_PATH", $default);
        return $dir ? sprintf("%s/%s", $build_path, $dir) : $build_path;
    }

    public static function helpPath(?string $dir = null): ?string
    {
        $default   = self::basePath("docs/commands");
        $help_path = is_phar() ? $default : self::get("COMMAND_HELP_PATH", $default);
        return $dir ? sprintf("%s/%s", $help_path, $dir) : $help_path;
    }

    public static function isDebug(): bool
    {
        return self::get("APP_DEBUG", false);
    }

    public static function appVersion(): string
    {
        return git_version(self::basePath()) ?? self::get("APP_VERSION", "unreleased");
    }

    public static function appName(): string
    {
        return self::get("APP_NAME", "cosmic");
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        /** @psalm-suppress UndefinedFunction */
        return Option::fromValue(static::getRepository()->get($key))
            ->map(function ($value) {
                switch (strtolower($value)) {
                    case 'true':
                    case '(true)':
                        return true;
                    case 'false':
                    case '(false)':
                        return false;
                    case 'empty':
                    case '(empty)':
                        return '';
                    case 'null':
                    case '(null)':
                        return null;
                }

                if (preg_match('/\A([\'"])(.*)\1\z/', $value, $matches)) {
                    return $matches[2];
                }

                return $value;
            })
            ->getOrCall(fn() => value($default));
    }
}
