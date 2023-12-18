<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Crypt;

use Ninja\Cosmic\Crypt\Exception\PGPNotInstalledException;
use Ninja\Cosmic\Exception\BinaryNotFoundException;
use Symfony\Component\Process\Process;

use function Cosmic\find_binary;

class Verifier implements VerifierInterface
{
    public static function verify(string $filePath): bool
    {
        try {
            $command = sprintf(
                '%s --verify %s %s',
                find_binary("gpg"),
                AbstractKey::getSignatureFilePath($filePath),
                $filePath
            );

            $process = Process::fromShellCommandline($command);
            if ($process->mustRun()->isSuccessful()) {
                $output = $process->getErrorOutput();
                return str_contains($output, 'Good signature');
            }

            return false;

        } catch (BinaryNotFoundException $e) {
            throw new PGPNotInstalledException(
                message: "GPG binary not found. Please install it and try again.",
                previous: $e
            );
        }
    }
}
