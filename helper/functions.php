<?php

declare(strict_types=1);

namespace Cosmic;

use Closure;
use Ninja\Cosmic\Environment\Env;
use Ninja\Cosmic\Environment\EnvironmentBuilder;
use Ninja\Cosmic\Environment\Exception\EnvironmentNotFoundException;
use Ninja\Cosmic\Exception\BinaryNotFoundException;
use Ninja\Cosmic\Exception\UnexpectedValueException;
use Ninja\Cosmic\Replacer\ReplacerFactory;
use Ninja\Cosmic\Terminal\Input\Question;
use Ninja\Cosmic\Terminal\Terminal;
use Phar;
use ReflectionException;
use ReflectionNamedType;
use ReflectionProperty;
use Symfony\Component\Process\Process;

if (!function_exists('Cosmic\snakeize')) {
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
    function camelize(string $input): string
    {
        $input = str_replace(' ', '', ucwords(str_replace('_', ' ', $input)));
        return lcfirst($input);
    }
}

if (!function_exists('Cosmic\value')) {
    function value(mixed $value, mixed ...$args): mixed
    {
        return $value instanceof Closure ? $value(...$args) : $value;
    }
}

if (!function_exists('Cosmic\is_phar')) {
    function is_phar(): bool
    {
        if (is_cosmic()) {
            return false;
        }

        return Phar::running() !== '';
    }
}

if (!function_exists('Cosmic\is_git')) {
    function is_git(): bool
    {
        $command = sprintf("%s rev-parse --is-inside-work-tree", find_binary("git"));
        $process = Process::fromShellCommandline($command);
        $process->run();

        return $process->isSuccessful();
    }
}

if (!function_exists('Cosmic\find_binary')) {
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
    function get_class_from_file(string $file): string
    {
        $class_name = basename($file, ".php");
        $namespace  = get_namespace_from_file($file);

        return $namespace . "\\" . $class_name;
    }
}

if (!function_exists("Cosmic\git_version")) {
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
    function is_root(): bool
    {
        return posix_getuid() === 0;
    }
}

if (!function_exists("Cosmic\is_nullable")) {
    /**
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

if (!function_exists('Cosmic\sudo')) {
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
    function mask(string $string, ?int $length = null): string
    {
        $length = $length ?? strlen($string);
        return str_repeat('*', $length);
    }
}

if (!function_exists('Cosmic\pluralize')) {
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
    function replace(string $string): string
    {
        return ReplacerFactory::r($string);
    }
}

if (!function_exists('Cosmic\is_cosmic')) {
    function is_cosmic(): bool
    {
        $check = sprintf("%s/vendor/diego-ninja/cosmic", getcwd());
        return file_exists($check);
    }
}

if (!function_exists('Cosmic\randomize')) {
    function randomize(int $length): string
    {
        if (extension_loaded('openssl')) {
            return bin2hex(openssl_random_pseudo_bytes($length));
        }

        return bin2hex(random_bytes($length));
    }
}

if (!function_exists('Cosmic\termwindize')) {
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
    function cypher(string $plain, string $key): string
    {
        $cipher = "AES-128-CBC";

        $iv_length      = openssl_cipher_iv_length($cipher);
        $iv             = openssl_random_pseudo_bytes($iv_length);
        $ciphertext_raw = openssl_encrypt($plain, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        $hmac           = hash_hmac('sha256', $ciphertext_raw, $key, true);

        return base64_encode($iv . $hmac . $ciphertext_raw);
    }
}

if (!function_exists('Cosmic\decipher')) {
    function decipher(string $cipher_text, string $key): string
    {
        $cipher  = "AES-128-CBC";
        $sha2len = 32;

        $c              = base64_decode($cipher_text);
        $iv_length      = openssl_cipher_iv_length($cipher);
        $iv             = substr($c, 0, $iv_length);
        $ciphertext_raw = substr($c, $iv_length + $sha2len);

        return openssl_decrypt($ciphertext_raw, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    }
}

if (!function_exists('Cosmic\human_filesize')) {
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
    function gradient(string $from, string $to, $graduations = 10): array
    {
        $ret = [];

        $graduations--;
        $start = str_replace("#", "", $from);
        $end = str_replace("#", "", $to);

        $red = hexdec(substr($start, 0, 2));
        $green = hexdec(substr($start, 2, 2));
        $blue = hexdec(substr($start, 4, 2));

        if ($graduations >= 2) { // for at least 3 colors
            $GradientSizeRed = (hexdec(substr($end, 0, 2)) - $red) / $graduations; //Graduation Size Red
            $GradientSizeGrn = (hexdec(substr($end, 2, 2)) - $green) / $graduations;
            $GradientSizeBlu = (hexdec(substr($end, 4, 2)) - $blue) / $graduations;
            for ($i = 0; $i <= $graduations; $i++) {
                $grad_red = (int) ($red + ($GradientSizeRed * $i));
                $grad_green = (int) ($green + ($GradientSizeGrn * $i));
                $grad_blue = (int) ($blue + ($GradientSizeBlu * $i));

                $ret[$i] = strtoupper("#" . str_pad(dechex($grad_red), 2, '0', STR_PAD_LEFT) .
                    str_pad(dechex($grad_green), 2, '0', STR_PAD_LEFT) .
                    str_pad(dechex($grad_blue), 2, '0', STR_PAD_LEFT));
            }
        } elseif ($graduations === 1) {
            $ret[] = $from;
            $ret[] = $to;
        } else { // one color
            $ret[] = $from;
        }
        return $ret;
    }
}
