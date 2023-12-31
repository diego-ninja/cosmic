<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command;

use Exception;
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
use Ninja\Cosmic\Exception\MissingInterfaceException;
use Ninja\Cosmic\Exception\UnexpectedValueException;
use Ninja\Cosmic\Installer\AptInstaller;
use Ninja\Cosmic\Terminal\Terminal;
use Ninja\Cosmic\Terminal\UI\Input\Question;
use Ninja\Cosmic\Terminal\UI\Spinner\SpinnerFactory;
use Ninja\Cosmic\Terminal\UI\UI;

use function Cosmic\find_binary;

#[Icon("🔑")]
#[Name("sign")]
#[Description("Sign the binary passed as parameter with the provided GPG key.")]
#[Signature("sign [-u|--user=] [-k|--key-id=] [binary]")]
#[Argument("binary", description: "The binary to sign")]
#[Option("--user", description: "The user email to sign the binary")]
#[Option("--key-id", description: "The key to sign the binary")]
#[Alias("app:sign")]
class SignCommand extends CosmicCommand
{
    /**
     * @throws BinaryNotFoundException
     * @throws MissingInterfaceException
     * @throws UnexpectedValueException
     */
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

    /**
     * @throws BinaryNotFoundException
     */
    private function hasGPG(): bool
    {
        $gpg = find_binary("gpg");
        if ($gpg === '' || $gpg === '0') {
            Terminal::output()->writeln("Binary <comment>gpg</comment> not found.");
            if (Question::confirm(
                sprintf("Do you want <info>%s</info> try to install the missing %s binary?", Env::get("APP_NAME"), "gpg")
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

    /**
     * @throws UnexpectedValueException
     * @throws MissingInterfaceException
     * @throws Exception
     */
    private function tryUserSign(string $binary, string $user): bool
    {
        $keyring  = KeyRing::public();
        $user_key = $keyring->all()->getByEmail($user);

        if (is_array($user_key)) {
            $keyId    = $this->selectKey($user_key, $user);
            $user_key = $keyring->all()->getById($keyId);
        }

        if ($user_key) {
            UI::p("Using the following GPG key to sign the selected file:");
            $user_key->render(Terminal::output());

            if (Question::confirm("Do you want to use this key to sign the binary?")) {
                $this->executionResult = $this->sign($binary, $user_key);
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<string,AbstractKey> $keys
     * @param string $user
     * @return string
     */
    private function selectKey(array $keys, string $user): string
    {
        Terminal::output()->writeln(
            sprintf("Multiple keys found for user <comment>%s</comment>. Please select one from the list.", $user)
        );

        $options = [];
        foreach ($keys as $key) {
            $options[$key->id] = (string)$key;
        }

        $selection = Question::select(
            message: "Select the key to use to sign the binary",
            options: $options,
            allowMultiple: false,
            maxWidth: 120
        )[0];

        return array_flip($options)[$selection];
    }

    /**
     * @throws UnexpectedValueException
     * @throws MissingInterfaceException
     * @throws Exception
     */
    private function tryKeyIdSign(string $binary, string $keyId): bool
    {
        $keyring = KeyRing::public();
        $key     = $keyring->all()->getById($keyId);

        if (!$key instanceof AbstractKey) {
            Terminal::output()->writeln(
                sprintf("Key <comment>%s</comment> does not exist.", $key)
            );
            return false;
        }

        Terminal::output()->writeln("");
        Terminal::output()->writeln(" Detected the following GPG key associated to the key id:");
        Terminal::output()->writeln("");
        $key->render(Terminal::output());

        if (Question::confirm("Do you want to use this key to sign the binary?")) {
            $this->executionResult = $this->sign($binary, $key);
            return true;
        }

        return false;
    }

    /**
     * @throws UnexpectedValueException
     * @throws MissingInterfaceException
     * @throws Exception
     */
    private function tryDefaultSign(string $binary): bool
    {
        $keyring = KeyRing::public();

        $default_key = Env::get("APP_SIGNING_KEY") ?
            $keyring->all()->getById(Env::get("APP_SIGNING_KEY")) :
            $keyring->all()->getByEmail(Env::get("APP_AUTHOR_EMAIL"));

        if (is_array($default_key)) {
            $key_id      = $this->selectKey($default_key, Env::get("APP_AUTHOR_EMAIL"));
            $default_key = $keyring->all()->getById($key_id);
        }

        if ($default_key) {
            Terminal::output()->writeln("");
            Terminal::output()->writeln(" Detected the following GPG key associated to the author email:");
            Terminal::output()->writeln("");
            $default_key->render(Terminal::output());

            if (Question::confirm("Do you want to use this key to sign the binary?")) {
                $this->executionResult = $this->sign($binary, $default_key);
                return true;
            }
        }

        return false;
    }

    /**
     * @throws Exception
     */
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

}
