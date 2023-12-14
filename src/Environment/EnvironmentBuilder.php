<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Environment;

use Ninja\Cosmic\Environment\Exception\EnvironmentNotFoundException;

use function Cosmic\randomize;

class EnvironmentBuilder
{
    public static function build(string $directory): bool
    {
        $example_file = sprintf("%s/.env.example", $directory);
        if (file_exists($example_file)) {
            return (new self())->buildFrom($example_file);
        }

        throw EnvironmentNotFoundException::forEnv($directory . "/.env.example");

    }

    public function buildFrom(string $example_file): bool
    {
        return
            $this->buildEnvFile($example_file, sprintf("%s/.env", dirname($example_file))) && $this->buildEnvFile($example_file, sprintf("%s/.env.local", dirname($example_file)));

    }

    private function extractVariables(string $example_file, bool $get_values = false): array
    {
        $vars  = [];
        $lines = file($example_file, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            if ($line !== "" && !str_starts_with($line, "#")) {
                [$key, $value] = explode("=", $line);
                if ($get_values) {
                    $vars[$key] = $value;
                } else {
                    $vars[] = $key;
                }
            }
        }

        return $vars;
    }

    private function buildEnvFile(string $example_file, string $env_file): bool
    {
        $env_vars = $this->extractVariables($example_file, true);
        foreach ($env_vars as $key => $value) {
            if ($value === "") {
                if ($key === "APP_KEY") {
                    $env_vars[$key] = randomize(32);
                }
            }
        }

        $env_data = "";
        foreach ($env_vars as $var => $value) {
            $env_data .= "\n" . $var . "=" . $value;
        }

        return (bool)file_put_contents($env_file, $env_data);
    }

}
