<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Environment;

use Ninja\Cosmic\Environment\Exception\EnvironmentNotFoundException;

use function Cosmic\randomize;

/**
 * Class EnvironmentBuilder
 *
 * Utility class for building environment files based on an example file.
 */
class EnvironmentBuilder
{
    /**
     * Build the environment file based on the provided directory.
     *
     * @param string $directory The directory containing the example environment file.
     *
     * @return bool True on success, false otherwise.
     *
     * @throws EnvironmentNotFoundException If the example environment file is not found.
     */
    public static function build(string $directory): bool
    {
        $example_file = sprintf("%s/.env.example", $directory);
        if (file_exists($example_file)) {
            return (new self())->buildFrom($example_file);
        }

        throw EnvironmentNotFoundException::forEnv($directory . "/.env.example");
    }

    /**
     * Build environment files based on the provided example file.
     *
     * @param string $example_file The path to the example environment file.
     *
     * @return bool True on success, false otherwise.
     */
    public function buildFrom(string $example_file): bool
    {
        return
            $this->buildEnvFile($example_file, sprintf("%s/.env", dirname($example_file))) &&
            $this->buildEnvFile($example_file, sprintf("%s/.env.local", dirname($example_file)));
    }

    /**
     * Extract environment variables from the provided example file.
     *
     * @param string $example_file The path to the example environment file.
     * @param bool   $get_values   Whether to retrieve values along with keys.
     *
     * @return array The list of environment variables.
     */
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

    /**
     * Build an environment file based on the provided example file.
     *
     * @param string $example_file The path to the example environment file.
     * @param string $env_file     The path to the target environment file.
     *
     * @return bool True on success, false otherwise.
     */
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
