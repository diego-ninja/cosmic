<?php

declare(strict_types=1);

use Ninja\Cosmic\Config\Env;
use Ninja\Cosmic\Exception\BinaryNotFoundException;
use Symfony\Component\Process\Process;

if (!function_exists('snakeize')) {
    function snakeize(string $input): string
    {
        $pattern = '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!';
        preg_match_all($pattern, $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match === strtoupper($match) ?
                strtolower($match) :
                lcfirst($match);
        }
        return implode('_', $ret);
    }
}

if (!function_exists('camelize')) {
    function camelize(string $input): string
    {
        $input = str_replace(' ', '', ucwords(str_replace('_', ' ', $input)));
        return lcfirst($input);
    }
}

if (!function_exists('value')) {
    function value(mixed $value, mixed ...$args): mixed
    {
        return $value instanceof Closure ? $value(...$args) : $value;
    }
}

if (!function_exists('is_phar')) {
    function is_phar(): bool
    {
        if (Env::get("PHAR_ENABLED", true)) {
            return Phar::running() !== '';
        }

        return false;
    }
}

if (!function_exists('find_binary')) {
    function find_binary(string $binary): string
    {
        $command = sprintf("which %s", $binary);
        $process = Process::fromShellCommandline($command);
        $process->run();
        if ($process->isSuccessful()) {
            $binary_path = trim($process->getOutput());
            // Exclude Windows binaries (found in /mnt on WSL)
            if (!str_starts_with($binary_path, "/mnt")) {
                return $binary_path;
            }
        }

        throw BinaryNotFoundException::withBinary($binary);
    }
}

if (!function_exists("get_namespace_from_file")) {
    function get_namespace_from_file(string $file): ?string
    {
        $ns     = null;
        $handle = fopen($file, 'rb');
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (str_starts_with($line, 'namespace')) {
                    $parts = explode(' ', $line);
                    $ns    = rtrim(trim($parts[1]), ';');
                    break;
                }
            }
            fclose($handle);
        }
        return $ns;
    }
}

if (!function_exists("get_class_from_file")) {
    function get_class_from_file(string $file): string
    {
        $class_name = basename($file, ".php");
        $namespace  = get_namespace_from_file($file);

        return $namespace . "\\" . $class_name;
    }
}

if (!function_exists("git_version")) {
    function git_version(string $path): ?string
    {
        $command = sprintf("cd %s && %s describe --tags --abbrev=0", $path, find_binary("git"));
        $process = Process::fromShellCommandline($command);
        $process->run();

        if (!$process->isSuccessful()) {
            return null;
        }

        return trim($process->getOutput());
    }
}

if (!function_exists("is_root")) {
    function is_root(): bool
    {
        return posix_getuid() === 0;
    }
}

if (!function_exists('sudo')) {
    function sudo(string $command, ?string $sudo_passwd = null): string
    {
        if (!is_root()) {
            if (is_null($sudo_passwd)) {
                return sprintf("pkexec --disable-internal-agent %s", $command);
            }

            return sprintf("echo %s | sudo -S %s", $sudo_passwd, $command);
        }

        return $command;
    }
}

if (!function_exists('mask')) {
    function mask(string $string): string
    {
        $length = strlen($string);
        $mask   = str_repeat('*', $length);
        return substr_replace($string, $mask, 0, $length);
    }
}

if (!function_exists('pluralize')) {
    function pluralize(string $item): string
    {
        $lastChar = strtolower($item[strlen($item) - 1]);
        if ($lastChar === 's') {
            return $item . 'es';
        }

        if ($lastChar === 'y') {
            return substr($item, 0, -1) . 'ies';
        }

        return $item . 's';
    }
}
