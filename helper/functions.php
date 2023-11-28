<?php

declare(strict_types=1);

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

if (! function_exists('value')) {
    function value(mixed $value, ...$args): mixed
    {
        return $value instanceof Closure ? $value(...$args) : $value;
    }
}

if (!function_exists('is_phar')) {
    function is_phar(): bool
    {
        return Phar::running() !== '';
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

        throw new RuntimeException(
            sprintf("%s binary not found. Please install it before continue.", $binary)
        );
    }
}

if (!function_exists("get_namespace_from_file")) {
    function get_namespace_from_file($file): ?string
    {
        $ns = null;
        $handle = fopen($file, 'rb');
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (str_starts_with($line, 'namespace')) {
                    $parts = explode(' ', $line);
                    $ns = rtrim(trim($parts[1]), ';');
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
        $namespace = get_namespace_from_file($file);

        return $namespace . "\\" . $class_name;
    }
}


if (!function_exists("git_version")) {
    function git_version(string $path): string
    {
        $command = sprintf("cd %s && %s describe --tags --abbrev=0", $path, find_binary("git"));
        $process = Process::fromShellCommandline($command);
        $process->run();

        return trim($process->getOutput());
    }
}
