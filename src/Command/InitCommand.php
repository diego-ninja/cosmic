<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command;

use Ninja\Cosmic\Crypt\AbstractKey;
use RuntimeException;
use Exception;
use Ninja\Cosmic\Command\Attribute\Alias;
use Ninja\Cosmic\Command\Attribute\Argument;
use Ninja\Cosmic\Command\Attribute\Description;
use Ninja\Cosmic\Command\Attribute\Icon;
use Ninja\Cosmic\Command\Attribute\Name;
use Ninja\Cosmic\Command\Attribute\Option;
use Ninja\Cosmic\Command\Attribute\Signature;
use Ninja\Cosmic\Crypt\KeyRing;
use Ninja\Cosmic\Environment\Env;
use Ninja\Cosmic\Exception\BinaryNotFoundException;
use Ninja\Cosmic\Notifier\NotifiableInterface;
use Ninja\Cosmic\Terminal\Terminal;
use Ninja\Cosmic\Terminal\UI\Input\Question;
use Ninja\Cosmic\Terminal\UI\Spinner\SpinnerFactory;
use Ninja\Cosmic\Terminal\UI\UI;
use Phar;
use Symfony\Component\Process\Process;
use ZipArchive;

use function Cosmic\cypher;
use function Cosmic\find_binary;
use function Cosmic\git_config;
use function Cosmic\is_phar;
use function Cosmic\mask;
use function Cosmic\randomize;
use function Cosmic\unzip;

#[Icon("🛠️")]
#[Name("init")]
#[Description("Initialize and configure a new <info>cosmic</info> application")]
#[Signature("init [--path=] [name]")]
#[Argument("name", "The name of the new application", null)]
#[Option("--path", "The path to initialize the new application in", null)]
#[Alias("app:init")]
final class InitCommand extends CosmicCommand implements NotifiableInterface
{
    public const LICENSES = [
        "Apache-2.0",
        "BSD-2-Clause",
        "BSD-3-Clause",
        "GPL-2.0-only",
        "GPL-2.0-or-later",
        "GPL-3.0-only",
        "GPL-3.0-or-later",
        "LGPL-2.1-only",
        "LGPL-2.1-or-later",
        "LGPL-3.0-only",
        "LGPL-3.0-or-later",
        "MIT",
        "MPL-2.0",
        "Proprietary",
        "Unlicensed",
    ];

    /**
     * @var array<string,mixed>
     */
    private static array $replacements = [];

    /**
     * @throws Exception
     */
    public function __invoke(?string $name, ?string $path): int
    {
        UI::header(" Welcome to the <span class='text-gray700'>cosmic</span> application initializer");
        UI::p(
            "This utility will walk you through creating a new <info>cosmic</info> application.
                     Press <notice>^C</notice> at any time to quit."
        );

        $this->askPackageName($name);
        $this->askApplicationPath($path);
        $this->askApplicationDescription();
        $this->askApplicationAuthor();
        $this->askApplicationWebsite();
        $this->askApplicationLicense();
        $this->askDisableCosmicCommands();
        $this->askGenerateGPGKey();
        $this->askSudoPassword();

        if (Question::confirm("Do you confirm generation of the application?")) {
            Terminal::output()->writeln("");
            Terminal::output()->writeln(
                sprintf(
                    " %s Generating <info>cosmic</info> application",
                    Terminal::getTheme()?->getAppIcon()
                )
            );

            $this->executionResult = $this->expandApplication() && $this->replacePlaceholders() && $this->installApplicationDependencies() && $this->renameApplication();

            if ($this->executionResult) {
                Terminal::output()->writeln("");
                Terminal::output()->writeln(
                    sprintf(
                        " %s <info>%s</info> application generated. Happy coding!",
                        Terminal::getTheme()?->getAppIcon(),
                        self::$replacements["{app.name}"]
                    )
                );
            }
        }

        return $this->success();
    }

    /**
     * @throws Exception
     */
    private function expandApplication(): bool
    {
        return SpinnerFactory::for(static function (): bool {
            if (is_phar()) {
                $tmp_dir = sys_get_temp_dir() . "/" . uniqid("cosmic", true);

                $phar = new Phar(Phar::running(false));
                $phar->extractTo($tmp_dir, "resources/app.zip", true);
                if (file_exists($tmp_dir . "/resources/app.zip")) {
                    $application = $tmp_dir . "/resources/app.zip";
                    return unzip($application, self::$replacements["{app.path}"]);
                }

                return false;
            }

            $application = Env::basePath("resources/app.zip");
            $zip         = new ZipArchive();
            if ($zip->open($application) === true) {
                $zip->extractTo(self::$replacements["{app.path}"]);
                $zip->close();
                return true;
            }
            return false;
        }, sprintf("Expanding application template into <info>%s</info>", self::$replacements["{app.path}"]));

    }

    /**
     * @throws Exception
     */
    private function replacePlaceholders(): bool
    {
        return SpinnerFactory::for(static function (): bool {
            $files = ["composer.json", "box.json", "env", "src/Command/QuoteCommand.php", "docs/commands/quote.md"];
            if (file_exists(self::$replacements["{app.path}"])) {
                foreach ($files as $file) {
                    $content = file_get_contents(self::$replacements["{app.path}"] . "/$file");
                    if ($content === false) {
                        continue;
                    }

                    $content = str_replace(array_keys(self::$replacements), array_values(self::$replacements), $content);
                    file_put_contents(self::$replacements["{app.path}"] . "/$file", $content);
                }
            }

            return true;

        }, "Configuring and tweaking application");

    }

    private function installApplicationDependencies(): bool
    {
        $command = sprintf("cd %s && composer install", self::$replacements["{app.path}"]);
        return SpinnerFactory::for(
            callable: Process::fromShellCommandline($command),
            message: "Installing application dependencies"
        );
    }

    /**
     * @throws Exception
     */
    private function renameApplication(): bool
    {
        $command = sprintf(
            "cd %s && mv cosmic-app %s && mv env .env && cp .env .env.local && mkdir .cosmic",
            self::$replacements["{app.path}"],
            self::$replacements["{app.name}"]
        );
        return SpinnerFactory::for(
            callable: Process::fromShellCommandline($command),
            message: "Renaming application"
        );
    }

    private function askPackageName(?string $name = null): void
    {
        $default_name = $name ?? sprintf("%s/cosmic-app", get_current_user());
        $package_name = Question::ask(
            message: sprintf(" %s <question>Package name</question> (vendor/name):", "📦"),
            default: $default_name,
            decorated: false
        );
        if ($package_name) {
            [,$binary_name] = explode("/", $package_name);

            Terminal::clear(2);
            Terminal::output()->writeln("");
            Terminal::output()->writeln(sprintf(" %s Package name: <info>%s</info>", Terminal::getTheme()?->getIcon("bullet"), $package_name));
            Terminal::output()->writeln(sprintf(" %s Binary name: <info>%s</info>", Terminal::getTheme()?->getIcon("bullet"), $binary_name));
            Terminal::output()->writeln("");

            self::$replacements["{app.name}"]     = $binary_name;
            self::$replacements["{app.root}"]     = ucfirst($binary_name);
            self::$replacements["{package.name}"] = $package_name;
        }
    }

    private function askApplicationPath(?string $path = null): void
    {
        $default_path = $path ?? getcwd();
        $path         = Question::ask(
            message: " 📁 <question>Application path</question>:",
            default: (string)$default_path,
            decorated: false
        );

        if ($path) {
            if (!is_dir($path) && !mkdir($path, 0777, true) && !is_dir($path)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
            }

            Terminal::clear(2);
            Terminal::output()->writeln(sprintf(" %s Application path: <info>%s</info>", Terminal::getTheme()?->getIcon("bullet"), $path));
            Terminal::output()->writeln("");

            self::$replacements["{app.path}"] = $path;
        }

    }

    private function askApplicationDescription(): void
    {
        $description = Question::ask(message: " 📄 <question>Description</question>:", decorated: false);

        Terminal::clear(2);
        Terminal::output()->writeln(sprintf(" %s Description: <info>%s</info>", Terminal::getTheme()?->getIcon("bullet"), $description));
        Terminal::output()->writeln("");

        self::$replacements["{app.description}"] = $description;
    }

    /**
     * @throws BinaryNotFoundException
     */
    private function askApplicationAuthor(): void
    {
        $author = Question::ask(message: " 🥷 <question>Author</question>:", default: git_config("user.name"), decorated: false);
        $email  = Question::ask(message: " 📧 <question>E-Mail</question>:", default: git_config("user.email"), decorated: false);

        Terminal::clear(3);
        Terminal::output()->writeln(sprintf(" %s Author: <info>%s</info> <%s>", Terminal::getTheme()?->getIcon("bullet"), $author, $email));
        Terminal::output()->writeln("");

        self::$replacements["{author.name}"]  = $author;
        self::$replacements["{author.email}"] = $email;
    }

    /**
     * @throws BinaryNotFoundException
     */
    private function askApplicationWebsite(): void
    {
        $website = Question::ask(message: " 🌎 <question>Website</question>:", default: git_config("user.website"), decorated: false);

        Terminal::clear(2);
        Terminal::output()->writeln(sprintf(" %s Website: <info>%s</info>", Terminal::getTheme()?->getIcon("bullet"), $website));
        Terminal::output()->writeln("");

        self::$replacements["{author.url}"] = $website;
    }

    private function askApplicationLicense(): void
    {
        $license = Question::select(
            message: " 🔎 <question>License</question>: ",
            options: self::LICENSES,
            allowMultiple: false,
            columns: 3,
            maxWidth: 90
        );

        Terminal::clear(1);
        Terminal::output()->writeln(sprintf(" %s License: <info>%s</info>", Terminal::getTheme()?->getIcon("bullet"), $license[0]));
        Terminal::output()->writeln("");

        self::$replacements["{app.license}"] = $license[0];
    }

    private function askDisableCosmicCommands(): void
    {

        UI::title("<span class='mr-1'>🛸</span> Disable cosmic commands");
        UI::p(
            "Cosmic commands are a set of commands that are provided by the <info>cosmic</info> framework.
                     These commands are used to build, package and sign your application.
                     If you want to disable these commands, you can do so by setting this option to <notice>no</notice>.
                     If you want to keep these commands, just press <notice>return</notice> to skip this step."
        );

        $disable = Question::confirm(message: " 🛸 <question>Do you want to disable cosmic commands</question>:", default: false, decorated: false);

        Terminal::clear(10);
        Terminal::output()->writeln(sprintf(" %s Cosmic commands: <info>%s</info>", Terminal::getTheme()?->getIcon("bullet"), $disable ? "disabled" : "enabled"));
        Terminal::output()->writeln("");

        self::$replacements["{app.disable_cosmic_commands}"] = $disable ? "true" : "false";
    }

    /**
     * @throws Exception
     */
    private function askSudoPassword(): void
    {
        UI::title("<span class='mr-1'>#️⃣</span> Sudo password");
        UI::p(
            "Provide the sudo password if you want to perform root operations automatically.
                     This password is encrypted and stored in the <info>.env</info> file.
                     This password is not persisted in the application builds.
                     If you don't want to store the password, just press <notice>return</notice> to skip this step."
        );

        $password = Question::hidden(message: " #️⃣  <question>Sudo password</question>:", decorated: false);
        if ($password) {
            $key = randomize(32);

            Terminal::clear(10);
            Terminal::output()->writeln(sprintf(" %s Sudo password: <info>%s</info>", Terminal::getTheme()?->getIcon("bullet"), mask($password, 10)));
            Terminal::output()->writeln("");

            self::$replacements["{app.key}"]       = $key;
            self::$replacements["{sudo.password}"] = cypher($password, $key);

        } else {
            Terminal::clear(10);
            Terminal::output()->writeln("");
        }
    }

    /**
     * @throws Exception
     */
    private function askGenerateGPGKey(): void
    {
        $keyring = KeyRing::public();

        $default_key = Env::get("APP_SIGNING_KEY") ?
            $keyring->all()->getById(Env::get("APP_SIGNING_KEY")) :
            $keyring->all()->getByEmail(Env::get("APP_AUTHOR_EMAIL"));

        if (is_array($default_key)) {
            $key_id      = $this->selectKey($default_key, Env::get("APP_AUTHOR_EMAIL"));
            $default_key = $keyring->all()->getById($key_id);
        }

        UI::title("🔑 GPG key");
        UI::p(
            "Provide or generate the GPG key if you want to sign your application builds.
                     If you want to generate a new key, use <notice>generate</notice> to generate a new key.
                     If you want to use an existing key, just paste the key id and press <notice>return</notice>.
                     If you don't want to store the key right just press <notice>return</notice> to skip this step.
                     "
        );

        $key = Question::ask(
            message: " 🔑 <question>GPG key</question>:",
            default: $default_key?->id,
            autoComplete: ["generate", "skip"],
            decorated: false
        );

        if ($key === "skip") {
            return;
        }

        if ($key === "generate") {
            $key = $this->generateGPGKey();
        }

        if ($key) {
            Terminal::clear(11);
            Terminal::output()->writeln(sprintf(" %s GPG key: <info>%s</info>", Terminal::getTheme()?->getIcon("bullet"), $key));
            Terminal::output()->writeln("");

            self::$replacements["{gpg.key}"] = $key;
        }
    }

    /**
     * @throws BinaryNotFoundException
     * @throws Exception
     */
    private function generateGPGKey(): string
    {
        $command = sprintf(
            "%s --batch --passphrase '' --quick-generate-key '%s (%s) <%s>' rsa4096 sign,cert 2y",
            find_binary("gpg"),
            self::$replacements["{author.name}"],
            sprintf("GPG for %s", self::$replacements["{app.root}"]),
            self::$replacements["{author.email}"]
        );

        Terminal::output()->writeln("");
        $result = SpinnerFactory::for(
            callable: Process::fromShellCommandline($command),
            message: "Generating 🔑 GPG key for application signing..."
        );

        if (!$result) {
            throw new RuntimeException("Could not generate GPG key");
        }

        Terminal::clear(2);
        $key = KeyRing::public()->all()->getByEmail(self::$replacements["{author.email}"]);

        return is_array($key) ? $key[0]->id : $key->id;
    }

    public function getSuccessMessage(): string
    {
        return sprintf("Application %s successfully generated. Happy coding!", self::$replacements["{app.name}"]);
    }

    public function getErrorMessage(): string
    {
        return sprintf("Application %s could not be generated. Please try again.", self::$replacements["{app.name}"]);
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
}
