<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Signer;

use Ninja\Cosmic\Terminal\Spinner\SpinnerFactory;
use Symfony\Component\Process\Process;

class KeySigner extends AbstractSigner
{
    public function __construct(protected string $binary, protected string $key)
    {
        parent::__construct($binary);
    }

    public function sign(): bool
    {
        $this->unlinkSignature();
        return SpinnerFactory::for(
            callable: Process::fromShellCommandline($this->getCommand()),
            message: sprintf(
                "Signing binary <info>%s</info> with key 🔑 <notice>%s</notice>",
                $this->binary,
                $this->key
            )
        );
    }

    public function verify(): bool
    {
        return true;
    }

    private function getCommand(): string
    {
        return sprintf(
            "%s --default-key %s --detach-sign --output %s.asc %s",
            $this->gpg_path,
            $this->key,
            $this->binary,
            $this->binary
        );
    }
}
