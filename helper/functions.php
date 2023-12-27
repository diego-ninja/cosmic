<?php

declare(strict_types=1);

namespace Cosmic;

use Closure;
use Exception;
use Ninja\Cosmic\Environment\Env;
use Ninja\Cosmic\Environment\EnvironmentBuilder;
use Ninja\Cosmic\Environment\Exception\EnvironmentNotFoundException;
use Ninja\Cosmic\Exception\BinaryNotFoundException;
use Ninja\Cosmic\Exception\UnexpectedValueException;
use Ninja\Cosmic\Replacer\ReplacerFactory;
use Ninja\Cosmic\Terminal\Terminal;
use Ninja\Cosmic\Terminal\UI\Input\Question;
use Phar;
use ReflectionException;
use ReflectionNamedType;
use ReflectionProperty;
use Symfony\Component\Process\Process;

if (!function_exists('Cosmic\snakeize')) {
    /**
     * Convert a string to snake case.
     *
     * @param string $input
     * @return string
     */
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

if (!function_exists('Cosmic\camelize')) {
    /**
     * Convert a string to camel case.
     *
     * @param string $input
     * @return string
     */
    function camelize(string $input): string
    {
        $input = str_replace(' ', '', ucwords(str_replace('_', ' ', $input)));
        return lcfirst($input);
    }
}

if (!function_exists('Cosmic\colorize')) {
    /**
     * Colorize a string.
     *
     * @param string $text
     * @param string $color default: white
     * @return string
     */
    function colorize(string $text, string $color = "white"): string
    {
        return Terminal::color($color)->apply($text);
    }
}


if (!function_exists('Cosmic\value')) {
    /**
     * Return the default value of the given value.
     *
     * @param mixed $value
     * @param mixed ...$args
     * @return mixed
     */
    function value(mixed $value, mixed ...$args): mixed
    {
        return $value instanceof Closure ? $value(...$args) : $value;
    }
}

if (!function_exists('Cosmic\is_phar')) {
    /**
     * Check if the application is running as a Phar.
     *
     * @return bool
     */
    function is_phar(): bool
    {
        if (is_cosmic()) {
            return false;
        }

        return Phar::running() !== '';
    }
}

if (!function_exists('Cosmic\is_git')) {
    /**
     * Check if the current directory is a Git repository.
     * If path is provided, check if the path is a Git repository.
     *
     * @param string|null $path
     * @return bool
     * @throws BinaryNotFoundException
     */
    function is_git(?string $path = null): bool
    {
        $command = sprintf("%s rev-parse --is-inside-work-tree", find_binary("git"));
        $process = Process::fromShellCommandline($command);
        if (!is_null($path)) {
            $process->setWorkingDirectory($path);
        }
        $process->run();

        return $process->isSuccessful();
    }
}

if (!function_exists('Cosmic\find_binary')) {
    /**
     * Find a binary in the system.
     *
     * @param string $binary
     * @return string
     * @throws BinaryNotFoundException
     */
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

if (!function_exists("Cosmic\get_namespace_from_file")) {
    /**
     * Get the namespace from a file.
     *
     * @param string $file
     * @return string|null
     */
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

if (!function_exists("Cosmic\get_class_from_file")) {
    /**
     * Get the class name from a file.
     *
     * @param string $file
     * @return string
     */
    function get_class_from_file(string $file): string
    {
        $class_name = basename($file, ".php");
        $namespace  = get_namespace_from_file($file);

        return $namespace . "\\" . $class_name;
    }
}

if (!function_exists("Cosmic\git_version")) {
    /**
     * Get the current Git version for a given path
     *
     * @param string $path
     * @return string|null
     * @throws BinaryNotFoundException
     */
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

if (!function_exists("Cosmic\is_root")) {
    /**
     * Check if the current user is root.
     *
     * @return bool
     */
    function is_root(): bool
    {
        return posix_getuid() === 0;
    }
}

if (!function_exists("Cosmic\is_nullable")) {
    /**
     * Check if a property is nullable.
     *
     * @param string $property
     * @param string|null $classname
     *
     * @return bool
     * @throws UnexpectedValueException
     */
    function is_nullable(string $property, string $classname = null): bool
    {
        try {
            $type = (new ReflectionProperty($classname, $property))->getType();
            if ($type !== null) {
                /** @var ReflectionNamedType $type */
                return $type->allowsNull();
            }

            return false;
        } catch (ReflectionException $e) {
            throw UnexpectedValueException::fromException($e);
        }
    }
}

if (!function_exists("Cosmic\is_child")) {
    /**
     * Check if the current process is a child process.
     *
     * @return bool
     */
    function is_child(): bool
    {
        return posix_getppid() !== 0;
    }
}

if (!function_exists('Cosmic\sudo')) {
    /**
     * Run a command with sudo.
     * If a password is provided, use it, if not, ask for it.
     *
     * @param string $command
     * @param string|null $sudo_passwd
     * @return string
     */
    function sudo(string $command, ?string $sudo_passwd = null): string
    {
        if (!is_root()) {
            if (is_null($sudo_passwd) || $sudo_passwd === "") {
                return sprintf("pkexec --disable-internal-agent %s", $command);
            }

            $password = decipher($sudo_passwd, Env::get("APP_KEY"));

            return sprintf("echo %s | sudo -S %s", $password, $command);
        }

        return $command;
    }
}

if (!function_exists('Cosmic\mask')) {
    /**
     * Mask a string.
     *
     * @param string $string
     * @param int|null $length
     * @return string
     */
    function mask(string $string, ?int $length = null): string
    {
        $length = $length ?? strlen($string);
        return str_repeat('*', $length);
    }
}

if (!function_exists('Cosmic\pluralize')) {
    /**
     * Pluralize a string.
     *
     * @param string $item
     * @return string
     */
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

if (!function_exists('Cosmic\git_config')) {
    /**
     * Get a Git config value.
     *
     * @param string $key
     * @return string|null
     * @throws BinaryNotFoundException
     */
    function git_config(string $key): ?string
    {
        $command = sprintf("%s config --global --get %s", find_binary("git"), $key);
        $process = Process::fromShellCommandline($command);
        $process->run();

        if (!$process->isSuccessful()) {
            return null;
        }

        return trim($process->getOutput());
    }
}

if (!function_exists('Cosmic\unzip')) {
    /**
     * Unzip a file.
     * If a destination path is provided, unzip the file there.
     * Otherwise, unzip the file in the current directory.
     *
     * @param string $file
     * @param string|null $destination_path
     * @return bool
     * @throws BinaryNotFoundException
     */
    function unzip(string $file, ?string $destination_path = null): bool
    {
        $command = sprintf("%s %s", find_binary("unzip"), $file);
        if (!is_null($destination_path)) {
            $command .= sprintf(" -d %s", $destination_path);
        }

        $process = Process::fromShellCommandline($command);
        $process->run();

        return $process->isSuccessful();
    }
}

if (!function_exists('Cosmic\find_env')) {
    /**
     * Find the environment file.
     * If the file is not found, ask the user if he wants to create it.
     *
     * @return string
     * @throws EnvironmentNotFoundException
     */
    function find_env(): string
    {
        $env_file = Terminal::input()->hasParameterOption(["--env", "-e"]) ?
            sprintf(".env.%s", Terminal::input()->getParameterOption(["--env", "-e"])) :
            ".env";

        $env_path = is_phar() ?
            sprintf("%s/%s", Phar::running(), $env_file) :
            sprintf("%s/%s", getcwd(), $env_file);

        if (file_exists($env_path)) {
            return $env_file;
        }

        if (Question::confirm(message: " 🤔 Environment files not found, create them now?", decorated: false)) {
            EnvironmentBuilder::build(getcwd());
            Terminal::reset();
            return find_env();
        }

        throw EnvironmentNotFoundException::forEnv($env_file);
    }
}

if (!function_exists('Cosmic\replace')) {
    /**
     * Replace a string with the current environment variables.
     *
     * @param string $string
     * @return string
     */
    function replace(string $string): string
    {
        return ReplacerFactory::r($string);
    }
}

if (!function_exists('Cosmic\is_cosmic')) {
    /**
     * Check if the current directory is a Cosmic project.
     *
     * @return bool
     */
    function is_cosmic(): bool
    {
        $check = sprintf("%s/vendor/diego-ninja/cosmic", getcwd());
        return file_exists($check);
    }
}

if (!function_exists('Cosmic\randomize')) {
    /**
     * Generate a random string.
     *
     * @param int $length
     * @return string
     * @throws Exception
     */
    function randomize(int $length): string
    {
        if (extension_loaded('openssl')) {
            return bin2hex(openssl_random_pseudo_bytes($length));
        }

        return bin2hex(random_bytes($length));
    }
}

if (!function_exists('Cosmic\termwindize')) {
    /**
     * Convert a symfony style string to termwind css.
     *
     * @param string $message
     * @return string
     */
    function termwindize(string $message): string
    {
        return preg_replace_callback(
            '/<(\w+)>(.*?)<\/\1>/s',
            static function ($matches) {
                $tag     = $matches[1];
                $content = $matches[2];
                return sprintf('<span class="text-%s">%s</span>', $tag, $content);
            },
            $message
        );
    }
}

if (!function_exists('Cosmic\cypher')) {
    /**
     * Cypher a string using a key and an algorithm.
     *
     * @param string $plain
     * @param string $key
     * @param string $algo
     * @return string
     */
    function cypher(string $plain, string $key, string $algo = "AES-128-CBC"): string
    {
        $iv_length      = openssl_cipher_iv_length($algo);
        $iv             = openssl_random_pseudo_bytes($iv_length);
        $ciphertext_raw = openssl_encrypt($plain, $algo, $key, OPENSSL_RAW_DATA, $iv);
        $hmac           = hash_hmac('sha256', $ciphertext_raw, $key, true);

        return base64_encode($iv . $hmac . $ciphertext_raw);
    }
}

if (!function_exists('Cosmic\decipher')) {
    /**
     * Decipher a string using a key and an algorithm.
     *
     * @param string $cipher_text
     * @param string $key
     * @param string $algo
     * @return string
     */
    function decipher(string $cipher_text, string $key, string $algo = "AES-128-CBC"): string
    {
        $sha2len = 32;

        $c              = base64_decode($cipher_text);
        $iv_length      = openssl_cipher_iv_length($algo);
        $iv             = substr($c, 0, $iv_length);
        $ciphertext_raw = substr($c, $iv_length + $sha2len);

        return openssl_decrypt($ciphertext_raw, $algo, $key, OPENSSL_RAW_DATA, $iv);
    }
}

if (!function_exists('Cosmic\human_filesize')) {
    /**
     * Convert bytes to human-readable format.
     *
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    function human_filesize(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow   = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow   = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return sprintf('%s %s', round($bytes, $precision), $units[$pow]);
    }
}

if (!function_exists('Cosmic\gradient')) {
    function gradient(string $from, string $to, int $variations = 10): array
    {
        $ret = [];

        $variations--;
        $start = str_replace("#", "", $from);
        $end = str_replace("#", "", $to);

        $red = hexdec(substr($start, 0, 2));
        $green = hexdec(substr($start, 2, 2));
        $blue = hexdec(substr($start, 4, 2));

        if ($variations >= 2) { // for at least 3 colors
            $GradientSizeRed = (hexdec(substr($end, 0, 2)) - $red) / $variations; //Graduation Size Red
            $GradientSizeGrn = (hexdec(substr($end, 2, 2)) - $green) / $variations;
            $GradientSizeBlu = (hexdec(substr($end, 4, 2)) - $blue) / $variations;
            for ($i = 0; $i <= $variations; $i++) {
                $grad_red = (int) ($red + ($GradientSizeRed * $i));
                $grad_green = (int) ($green + ($GradientSizeGrn * $i));
                $grad_blue = (int) ($blue + ($GradientSizeBlu * $i));

                $ret[$i] = strtoupper("#" . str_pad(dechex($grad_red), 2, '0', STR_PAD_LEFT) .
                    str_pad(dechex($grad_green), 2, '0', STR_PAD_LEFT) .
                    str_pad(dechex($grad_blue), 2, '0', STR_PAD_LEFT));
            }
        } elseif ($variations === 1) {
            $ret[] = $from;
            $ret[] = $to;
        } else { // one color
            $ret[] = $from;
        }
        return $ret;
    }
}
