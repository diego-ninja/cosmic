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
use Ninja\Cosmic\Installer\AptInstaller;
use Ninja\Cosmic\Signer\KeySigner;
use Ninja\Cosmic\Signer\UserSigner;
use Ninja\Cosmic\Terminal\Table\Column\TableColumn;
use Ninja\Cosmic\Terminal\Table\Table;
use Ninja\Cosmic\Terminal\Table\TableConfig;
use Ninja\Cosmic\Terminal\Terminal;

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
        if (!$this->hasGPG()) {
            throw BinaryNotFoundException::withBinary("gpg");
        }

        if (!file_exists($binary)) {
            Terminal::output()->writeln(
                sprintf("Binary file <comment>%s</comment> does not exist.", $binary)
            );
        }

        if (!$key && !$user) {
            $this->executionResult = $this->tryDefaultSign($binary);
        }

        if ($user) {
            $this->executionResult = $this->sign($binary, $user, UserSigner::findGPGKey($user));
        }

        if ($key) {
            $this->executionResult = $this->sign($binary, null, $key);
        }

        return $this->exit();
    }

    private function hasGPG(): bool
    {
        $gpg = find_binary("gpg");
        if (!$gpg) {
            Terminal::output()->writeln("Binary <comment>gpg</comment> not found.");
            if (Terminal::confirm(
                sprintf("Do you want <info>%s</info> try to install the missing %s binary?", Env::get("APP_NAME"), "gpg"),
                "yes"
            )) {
                $installer = new AptInstaller(Terminal::output());
                $installer->addPackage("gpg");
                $is_installed = $installer->install();
                if (!$is_installed) {
                    Terminal::output()->writeln("Unable to install <comment>gpg</comment> binary. Please install it manually.");
                    return false;
                }

                return true;
            }

            return false;
        }

        return true;

    }

    private function tryDefaultSign(string $binary): bool
    {
        $default_key = UserSigner::findGPGKey(Env::get("APP_AUTHOR_EMAIL"));
        if ($default_key) {
            Terminal::output()->writeln("Detected the following GPG key associated to the author email:");
            Terminal::output()->writeln("");
            $this->displayGPGKey($default_key, Env::get("APP_AUTHOR_EMAIL"));

            if (Terminal::confirm("Do you want to use this key to sign the binary?", "yes")) {
                $this->executionResult = $this->sign($binary, Env::get("APP_AUTHOR_EMAIL"), $default_key);
                return true;
            }
        }

        return false;
    }

    private function sign(string $binary, ?string $user, ?string $key): bool
    {
        return $user ? (new UserSigner($binary, $user, $key))->sign() : (new KeySigner($binary, $key))->sign();
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
            ->addColumn(new TableColumn(name: '', key: 'key', color: 'cyan'))
            ->addColumn((new TableColumn(name: '', key: 'value')));

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
}
