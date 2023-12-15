<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Signer;

use Ninja\Cosmic\Terminal\Spinner\SpinnerFactory;
use Symfony\Component\Process\Process;

final class UserSigner extends AbstractSigner
{
    public function __construct(protected string $binary, private readonly string $user, private readonly ?string $key)
    {
        parent::__construct($binary);
    }

    public function sign(): bool
    {
        $this->unlinkSignature();
        return SpinnerFactory::for(
            callable: Process::fromShellCommandline($this->getCommand()),
            message: sprintf(
                "Signing binary <comment>%s</comment> for user <info>%s</info> with key 🔑 <notice>%s</notice>",
                $this->binary,
                $this->user,
                $this->getKeyID($this->key)
            )
        );
    }

    private function getKeyID(string $key): string
    {
        preg_match(self::PGP_KEY_REGEX_PATTERN, $key, $matches);
        return sprintf("%s/%s", $matches["cypher"], $matches['key_id']);
    }

    private function getCommand(): string
    {
        return sprintf(
            "%s -u %s --detach-sign --output %s.asc %s",
            $this->gpg_path,
            $this->user,
            $this->binary,
            $this->binary
        );
    }

}
