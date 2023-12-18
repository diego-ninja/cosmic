<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Crypt;

use Ninja\Cosmic\Crypt\Exception\PGPNotInstalledException;
use Ninja\Cosmic\Crypt\Exception\SignatureFileNotFoundException;
use Ninja\Cosmic\Exception\BinaryNotFoundException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

use function Cosmic\find_binary;

final class PublicKey extends AbstractKey implements SignerInterface
{
    public function sign(string $file_path): bool
    {
        if (!$this->isAbleTo(KeyInterface::GPG_USAGE_SIGN)) {
            throw new \RuntimeException("This key is not able to sign");
        }

        try {
            $command = sprintf(
                "%s --batch --yes --default-key %s --detach-sign --output %s %s",
                find_binary("gpg"),
                $this->id,
                self::getSignatureFilePath($file_path),
                $file_path
            );

            $process = Process::fromShellCommandline($command);
            return $process->mustRun()->isSuccessful();

        } catch (BinaryNotFoundException $e) {
            throw new PGPNotInstalledException(message: "PGP is not installed on your system", previous: $e);
        } catch (ProcessFailedException $e) {
            return false;
        }
    }

    public function verify(string $file_path): bool
    {
        if (!$this->isAbleTo(KeyInterface::GPG_USAGE_SIGN)) {
            throw new \RuntimeException("This key is not able to sign/verify");
        }

        if (!file_exists(self::getSignatureFilePath($file_path))) {
            throw SignatureFileNotFoundException::for($file_path);
        }

        try {
            $command = sprintf(
                "%s --verify %s %s",
                find_binary("gpg"),
                self::getSignatureFilePath($file_path),
                $file_path
            );

            $process = Process::fromShellCommandline($command);
            return $process->mustRun()->isSuccessful();

        } catch (BinaryNotFoundException $e) {
            throw new PGPNotInstalledException(message: "PGP is not installed on your system", previous: $e);
        } catch (ProcessFailedException $e) {
            return false;
        }
    }

}
