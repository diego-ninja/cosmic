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
use Ninja\Cosmic\Crypt\AbstractKey;
use Ninja\Cosmic\Crypt\KeyRing;
use Ninja\Cosmic\Crypt\SignerInterface;
use Ninja\Cosmic\Environment\Env;
use Ninja\Cosmic\Exception\BinaryNotFoundException;
use Ninja\Cosmic\Installer\AptInstaller;
use Ninja\Cosmic\Terminal\Spinner\SpinnerFactory;
use Ninja\Cosmic\Terminal\Table\Column\TableColumn;
use Ninja\Cosmic\Terminal\Table\Table;
use Ninja\Cosmic\Terminal\Table\TableConfig;
use Ninja\Cosmic\Terminal\Terminal;

use function Cosmic\find_binary;

#[Icon("🔏")]
#[Name("sign")]
#[Description("Sign the binary passed as parameter with the provided GPG key.")]
#[Signature("sign [-u|--user=] [-k|--key-id=] [binary]")]
#[Argument("binary", description: "The binary to sign")]
#[Option("--user", description: "The user email to sign the binary")]
#[Option("--key-id", description: "The key to sign the binary")]
#[Alias("app:sign")]
class SignCommand extends CosmicCommand
{
    public function __invoke(string $binary, ?string $user, ?string $keyId): int
    {
        if (!$this->hasGPG()) {
            throw BinaryNotFoundException::withBinary("gpg");
        }

        if (!file_exists($binary)) {
            Terminal::output()->writeln(
                sprintf("Binary file <comment>%s</comment> does not exist.", $binary)
            );
        }

        if (!$keyId && !$user) {
            $this->executionResult = $this->tryDefaultSign($binary);
        }

        if ($user) {
            $this->executionResult = $this->tryUserSign($binary, $user);
        }

        if ($keyId) {
            $this->executionResult = $this->tryKeyIdSign($binary, $keyId);
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

    private function tryUserSign(string $binary, string $user): bool
    {
        $keyring  = KeyRing::public();
        $user_key = $keyring->all()->getByEmail($user);

        if (is_array($user_key)) {
            Terminal::output()->writeln(
                sprintf("Multiple keys found for user <comment>%s</comment>. Please select one from the list.", $user)
            );

            $options = [];
            foreach ($user_key as $key) {
                $options[$key->id] = (string)$key;
            }

            $keyId    = $this->selectKey($options);
            $user_key = $keyring->all()->getById($keyId);
        }

        Terminal::output()->writeln("");
        Terminal::output()->writeln("Using the following GPG key to sign the selected file:");
        Terminal::output()->writeln("");

        $this->displayGPGKey($user_key);

        if (Terminal::confirm("Do you want to use this key to sign the binary?", "yes")) {
            $this->executionResult = $this->sign($binary, $user_key);
            return true;
        }

        return false;
    }

    private function selectKey(array $keys): string
    {
        $selection = Terminal::select(
            message: "Select the key to use to sign the binary",
            options: $keys,
            allowMultiple: false,
            maxWidth: 120,
        )[0];

        return array_flip($keys)[$selection];
    }

    private function tryKeyIdSign(string $binary, string $keyId): bool
    {
        $keyring = KeyRing::public();
        $key     = $keyring->all()->getById($keyId);

        if (!$key) {
            Terminal::output()->writeln(
                sprintf("Key <comment>%s</comment> does not exist.", $key)
            );
            return false;
        }

        Terminal::output()->writeln("");
        Terminal::output()->writeln("Detected the following GPG key associated to the key id:");
        Terminal::output()->writeln("");
        $this->displayGPGKey($key);

        if (Terminal::confirm("Do you want to use this key to sign the binary?", "yes")) {
            $this->executionResult = $this->sign($binary, $key);
            return true;
        }

        return false;
    }

    private function tryDefaultSign(string $binary): bool
    {
        $keyring     = KeyRing::public();
        $default_key = $keyring->all()->getByEmail(Env::get("APP_AUTHOR_EMAIL"));

        if ($default_key) {
            Terminal::output()->writeln("");
            Terminal::output()->writeln("Detected the following GPG key associated to the author email:");
            Terminal::output()->writeln("");
            $this->displayGPGKey($default_key);

            if (Terminal::confirm("Do you want to use this key to sign the binary?", "yes")) {
                $this->executionResult = $this->sign($binary, $default_key);
                return true;
            }
        }

        return false;
    }

    private function sign(string $binary, AbstractKey $key): bool
    {
        return SpinnerFactory::for(
            callable: static function () use ($key, $binary): bool {
                if ($key instanceof SignerInterface) {
                    return $key->sign($binary);
                }

                return false;
            },
            message: sprintf("Signing binary <info>%s</info> with key 🔑 <comment>%s</comment>", $binary, $key->id),
        );

    }

    private function displayGPGKey(AbstractKey $key): void
    {
        $data   = $this->formatKeyData($key);
        $config = new TableConfig();
        $config->setShowHeader(false);
        $config->setPadding(1);

        $table = (new Table(data: $data, columns: [], config: $config))
            ->addColumn(new TableColumn(name: '', key: 'key', color: 'cyan'))
            ->addColumn((new TableColumn(name: '', key: 'value')));

        $table->display(Terminal::output());
    }

    private function formatKeyData(AbstractKey $key): array
    {
        return [
            'Key ID' => [
                "key"   => "Key ID",
                "value" => $key->id ?? null,
            ],
            'Method' => [
                "key"   => "Method",
                "value" => $key->method ?? null,
            ],
            'Created' => [
                "key"   => "Created",
                "value" => $key->createdAt->toFormattedDateString(),
            ],
            'Expires' => [
                "key"   => "Expires",
                "value" => $key->expiresAt ? $key->expiresAt->toFormattedDateString() : '',
            ],
            'Usage' => [
                "key"   => "Usage",
                "value" => $key->usage ?? null,
            ],
            'Fingerprint' => [
                "key"   => "Fingerprint",
                "value" => $key->fingerprint ?? null,
            ],
            'User ID' => [
                "key"   => "User ID",
                "value" => (string)$key->uid,
            ],
        ];
    }
}
