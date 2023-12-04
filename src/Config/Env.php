<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Config;

use Dotenv\Repository\Adapter\PutenvAdapter;
use Dotenv\Repository\RepositoryBuilder;
use Dotenv\Repository\RepositoryInterface;
use Ninja\Cosmic\Terminal\Terminal;
use Phar;
use PhpOption\Option;

use function Cosmic\is_phar;
use function Cosmic\mask;
use function Cosmic\value;
use function Cosmic\git_version;

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
        if (Terminal::input()->hasOption("debug")) {
            return Terminal::input()->getOption("debug") === true;
        }

        return self::get("APP_DEBUG", false);
    }

    public static function env(): string
    {
        if (Terminal::input()->hasOption("env")) {
            return Terminal::input()->getOption("env");
        }

        return self::get("APP_ENV", ENV_LOCAL);
    }

    public static function appVersion(): string
    {
        return git_version(self::basePath()) ?? self::get("APP_VERSION", "unreleased");
    }

    public static function appName(): string
    {
        return self::get("APP_NAME", "cosmic");
    }

    public static function dump(): array
    {
        $ret = [];
        foreach ($_ENV as $key => $value) {
            $var["key"]   = $key;
            $var["value"] = self::get($key);
            $ret[$key]    = $var;
        }

        $ret["APP_NAME"]["value"] = self::appName();

        $ret["BASE_PATH"]["key"]   = "BASE_PATH";
        $ret["BASE_PATH"]["value"] = self::basePath();

        $ret["BUILD_PATH"]["key"]   = "BUILD_PATH";
        $ret["BUILD_PATH"]["value"] = self::buildPath();

        $ret["COMMAND_HELP_PATH"]["key"]   = "COMMAND_HELP_PATH";
        $ret["COMMAND_HELP_PATH"]["value"] = self::helpPath();

        $ret["APP_VERSION"]["key"]   = "APP_VERSION";
        $ret["APP_VERSION"]["value"] = self::appVersion();

        if (self::get("SUDO_PASSWORD", "") !== "") {
            $ret["SUDO_PASSWORD"]["key"]   = "SUDO_PASSWORD";
            $ret["SUDO_PASSWORD"]["value"] = mask(self::get("SUDO_PASSWORD", ""));
        }

        sort($ret);

        return $ret;
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
