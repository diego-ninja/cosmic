<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command;

use Ninja\Cosmic\Command\Attribute\Alias;
use Ninja\Cosmic\Command\Attribute\Argument;
use Ninja\Cosmic\Command\Attribute\Description;
use Ninja\Cosmic\Command\Attribute\Icon;
use Ninja\Cosmic\Command\Attribute\Name;
use Ninja\Cosmic\Command\Attribute\Option;
use Ninja\Cosmic\Command\Attribute\Signature;
use Ninja\Cosmic\Environment\Env;
use Ninja\Cosmic\Exception\BinaryNotFoundException;
use Ninja\Cosmic\Terminal\Spinner\SpinnerFactory;
use Ninja\Cosmic\Terminal\Table\Column\TableColumn;
use Ninja\Cosmic\Terminal\Table\Table;
use Ninja\Cosmic\Terminal\Table\TableConfig;
use Ninja\Cosmic\Terminal\Terminal;

use Symfony\Component\Process\Process;

use function Cosmic\find_binary;

#[Icon("🔏")]
#[Name("sign")]
#[Description("Sign the binary passed as parameter with the provided GPG key.")]
#[Signature("sign [--user=] [--key=] [binary]")]
#[Argument("binary", description: "The binary to sign")]
#[Option("--user", description: "The user email to sign the binary")]
#[Option("--key", description: "The key to sign the binary")]
#[Alias("app:sign")]
class SignCommand extends CosmicCommand
{
    public function __invoke(string $binary, ?string $user, ?string $key): int
    {
        if (!file_exists($binary)) {
            Terminal::output()->writeln("Binary file <comment>{$binary}</comment> does not exist.");
            return $this->failure();
        }

        if (!$key && !$user) {
            $default_key = $this->findGPGKey(Env::get("APP_AUTHOR_EMAIL"));
            if ($default_key) {
                Terminal::output()->writeln("Detected the following GPG key associated to the author email:");
                Terminal::output()->writeln("");
                $this->displayGPGKey($default_key, Env::get("APP_AUTHOR_EMAIL"));

                if (Terminal::confirm("Do you want to use this key to sign the binary?", "yes")) {
                    $this->executionResult = $this->signBinary($binary, Env::get("APP_AUTHOR_EMAIL"), $default_key);
                    return $this->exit();
                }

                return $this->success();
            }
            Terminal::output()->writeln("You must provide a <comment>--user</comment> or <comment>--key</comment> to sign the binary.");
            return $this->failure();
        }

        if ($user) {
            $this->executionResult = $this->signBinary($binary, $user, $this->findGPGKey($user));
        }

        if ($key) {
            $this->executionResult = $this->signBinary($binary, null, $key);
        }

        return $this->exit();
    }

    private function signBinary(string $binary, ?string $user, ?string $key): bool
    {
        $gpg = find_binary("gpg");
        if (!$gpg) {
            throw BinaryNotFoundException::withBinary("gpg");
        }

        if (file_exists(sprintf("%s.asc", $binary))) {
            unlink(sprintf("%s.asc", $binary));
        }

        if ($user) {
            $command = sprintf("%s -u %s --detach-sign --output %s.asc %s", $gpg, $user, $binary, $binary);
            return SpinnerFactory::for(
                callable: Process::fromShellCommandline($command),
                message: sprintf(
                    "Signing binary <comment>%s</comment> for user <info>%s</info> with key <notice>%s</notice>",
                    $binary,
                    $user,
                    $this->getKeyID($key)
                )
            );
        }

        if ($key) {
            $command = sprintf("%s --default-key %s --detach-sign --output %s.asc %s", $gpg, $key, $binary, $binary);
            return SpinnerFactory::for(
                callable: Process::fromShellCommandline($command),
                message: sprintf("Signing binary <info>%s</info> with key <info>%s</info>", $binary, $key)
            );
        }

        return false;

    }

    private function findGPGKey(?string $user_email): ?string
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

    private function displayGPGKey(string $key, string $user): void
    {
        $data                  = $this->parseKey($key);
        $data["Associated to"] = [
            "key"   => "Associated to",
            "value" => $user,
        ];

        $config = new TableConfig();
        $config->setShowHeader(false);
        $config->setPadding(1);

        $table = (new Table(data: $data, columns: [], config: $config))
            ->addColumn(new TableColumn(name: 'ENV VAR', key: 'key', color: 'cyan'))
            ->addColumn((new TableColumn(name: 'VALUE', key: 'value')));

        $table->display(Terminal::output());
    }

    private function parseKey(string $keyInfo): array
    {
        $pattern = '/(?P<cypher>\w+)\/(?P<key_id>\w+)\s(?P<created_at>\d{4}-\d{2}-\d{2})\s\[(?P<usage>\w+)\]\s\[expires:\s(?P<expires_at>\d{4}-\d{2}-\d{2})\]/';
        preg_match($pattern, $keyInfo, $matches);

        return [
            'Key ID' => [
                "key"   => "Key ID",
                "value" => $matches['key_id'] ?? null,
            ],
            'Algorithm' => [
                "key"   => "Algorithm",
                "value" => $matches['cypher'] ?? null,
            ],
            'Generated at' => [
                "key"   => "Generated at",
                "value" => $matches['created_at'] ?? null,
            ],
            'Expires at' => [
                "key"   => "Expires at",
                "value" => $matches['expires_at'] ?? null,
            ],
            'Usage' => [
                "key"   => "Usage",
                "value" => $matches['usage'] ?? null,
            ],
        ];
    }

    private function getKeyID(string $key): string
    {
        $pattern = '/(?P<cypher>\w+)\/(?P<key_id>\w+)\s(?P<created_at>\d{4}-\d{2}-\d{2})\s\[(?P<usage>\w+)\]\s\[expires:\s(?P<expires_at>\d{4}-\d{2}-\d{2})\]/';
        preg_match($pattern, $key, $matches);

        return sprintf("%s/%s", $matches["cypher"], $matches['key_id']);
    }
}
