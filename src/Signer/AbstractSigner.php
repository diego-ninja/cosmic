<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Signer;

use Ninja\Cosmic\Environment\Env;
use Ninja\Cosmic\Exception\BinaryNotFoundException;
use Ninja\Cosmic\Signer\Exception\SignatureFileNotFoundException;
use Ninja\Cosmic\Terminal\Terminal;
use Symfony\Component\Process\Process;

use function Cosmic\find_binary;

abstract class AbstractSigner implements SignerInterface
{
    protected string $gpg_path;

    public function __construct(protected string $binary)
    {
        $this->gpg_path = find_binary("gpg");
        if (!$this->gpg_path) {
            throw BinaryNotFoundException::withBinary("gpg");
        }

    }

    public function verify(): bool
    {
        $signatureFile = $this->binary . '.asc';

        if (!file_exists($signatureFile)) {
            throw SignatureFileNotFoundException::for($this->binary);
        }

        $process = Process::fromShellCommandline(sprintf('%s --verify %s %s', $this->gpg_path, $signatureFile, $this->binary));
        $process->run();

        if ($process->isSuccessful()) {
            Terminal::output()->writeln(sprintf("Signature for binary <comment>%s</comment> is valid.", $this->binary));
            return true;
        }

        Terminal::output()->writeln(sprintf("Signature for binary <comment>%s</comment> is invalid.", $this->binary));
        return false;
    }

    protected function unlinkSignature(): void
    {
        $signature_file = sprintf("%s.%s", $this->binary, self::SIGNATURE_SUFFIX);
        if (file_exists($signature_file)) {
            unlink($signature_file);
        }
    }

    public static function findGPGKey(?string $user_email): ?string
    {
        $gpg = find_binary("gpg");
        if (!$gpg) {
            throw BinaryNotFoundException::withBinary("gpg");
        }

        $user_email = $user_email ?? Env::get("APP_AUTHOR_EMAIL");

        $command = sprintf("%s --list-keys --keyid-format LONG %s", $gpg, $user_email);
        $process = Process::fromShellCommandline($command);
        $process->run();

        if (!$process->isSuccessful()) {
            return null;
        }

        $output = $process->getOutput();
        $lines  = explode("\n", $output);
        $key    = null;
        foreach ($lines as $line) {
            if (str_starts_with($line, "pub")) {
                $parts = explode("  ", $line);
                $key   = trim($parts[1]);
                break;
            }
        }

        return $key;
    }

    abstract public function sign(): bool;
}
