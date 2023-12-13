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
use Ninja\Cosmic\Config\Env;
use Ninja\Cosmic\Notifier\NotifiableInterface;
use Ninja\Cosmic\Terminal\Spinner\SpinnerFactory;
use Ninja\Cosmic\Terminal\Terminal;
use Phar;
use ReflectionException;
use Symfony\Component\Process\Process;
use Termwind\Termwind;
use ZipArchive;

use function Cosmic\cypher;
use function Cosmic\mask;
use function Cosmic\randomize;
use function Termwind\render;
use function Cosmic\is_phar;
use function Cosmic\git_config;
use function Cosmic\unzip;

#[Icon("🛠️ ")]
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

    private static array $replacements = [];

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function __invoke(?string $name, ?string $path): int
    {
        Termwind::renderUsing(Terminal::header());
        render(
            sprintf(
                "<div class='m-1 bg-cyan p-4 w-80'>%s</div>",
                " Welcome to the <span class='text-grey'>cosmic</span> application initializer"
            )
        );

        Terminal::header()->writeln(" This utility will walk you through creating a new <info>cosmic</info> application.");
        Terminal::header()->writeln(" Press <comment>^C</comment> at any time to quit.");
        Terminal::header()->writeln("");

        $this->askPackageName();
        $this->askApplicationPath();
        $this->askApplicationDescription();
        $this->askApplicationAuthor();
        $this->askApplicationWebsite();
        $this->askApplicationLicense();
        $this->askSudoPassword();

        if (Terminal::confirm("Do you confirm generation of the application?", "yes")) {
            Terminal::body()->clear();
            Terminal::body()->writeln("");
            Terminal::body()->writeln(
                sprintf(
                    " %s Generating <info>cosmic</info> application",
                    Terminal::getTheme()->getAppIcon()
                )
            );

            Terminal::footer()->clear();
            $this->executionResult = $this->expandApplication() && $this->replacePlaceholders() && $this->installApplicationDependencies() && $this->renameApplication();

            if ($this->executionResult) {
                Terminal::body()->writeln("");
                Terminal::body()->writeln(
                    sprintf(
                        " %s <info>%s</info> application generated. Happy coding!",
                        Terminal::getTheme()->getAppIcon(),
                        self::$replacements["{app.name}"]
                    )
                );
                Terminal::footer()->clear();
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

    /**
     * @throws ReflectionException
     */
    private function askPackageName(): void
    {
        $default_name = sprintf("%s/cosmic-app", get_current_user());
        $package_name = Terminal::ask(
            message: sprintf("%s Package name (vendor/name): ", "📦"),
            default: $default_name,
            decorated: false
        );
        [,$binary_name] = explode("/", $package_name);

        Terminal::body()->writeln(" 📦 Package name: <info>$package_name</info>");
        Terminal::body()->writeln(" ⚙️  Binary name: <info>$binary_name</info>");
        Terminal::footer()->clear();

        self::$replacements["{app.name}"]     = $binary_name;
        self::$replacements["{app.root}"]     = ucfirst($binary_name);
        self::$replacements["{package.name}"] = $package_name;
    }

    /**
     * @throws ReflectionException
     */
    private function askApplicationPath(): void
    {
        $default_path = getcwd();
        $path         = Terminal::ask(message: "📁 Application path: ", default: $default_path, decorated: false);

        self::$replacements["{app.path}"] = $path;

        Terminal::body()->writeln(" 📁 Application path: <info>$path</info>");
        Terminal::footer()->clear();
    }

    /**
     * @throws ReflectionException
     */
    private function askApplicationDescription(): void
    {
        $description = Terminal::ask(message: "🔖 Description: ", decorated: false);
        Terminal::body()->writeln(" 🔖 Description: <info>$description</info>");
        Terminal::footer()->clear();

        self::$replacements["{app.description}"] = $description;
    }

    /**
     * @throws ReflectionException
     */
    private function askApplicationAuthor(): void
    {
        $author = Terminal::ask(message: " 🥷 Author: ", default: git_config("user.name"), decorated: false);
        Terminal::footer()->clear();
        $email = Terminal::ask(message: " 📧 E-Mail: ", default: git_config("user.email"), decorated: false);
        Terminal::body()->writeln(" 🥷 Author: <info>$author</info> <$email>");
        Terminal::footer()->clear();

        self::$replacements["{author.name}"]  = $author;
        self::$replacements["{author.email}"] = $email;
    }

    /**
     * @throws ReflectionException
     */
    private function askApplicationWebsite(): void
    {
        $website = Terminal::ask(message: " 🌎 Website: ", default: git_config("user.website"), decorated: false);
        Terminal::body()->writeln(" 🌎 Website: <info>$website</info>");
        Terminal::footer()->clear();

        self::$replacements["{author.url}"] = $website;
    }

    private function askApplicationLicense(): void
    {
        $license = Terminal::select(
            message: " 📄 License: ",
            options: self::LICENSES,
            allowMultiple: false,
            output: Terminal::output(),
            columns: 3,
            maxWidth: 90
        );

        Terminal::body()->writeln(" 📄 License: <info>$license[0]</info>");
        self::$replacements["{app.license}"] = $license[0];
    }

    /**
     * @throws ReflectionException
     */
    private function askSudoPassword(): void
    {
        $password = Terminal::ask(message: "🔑 Sudo password: ", hideAnswer: true, decorated: false);
        Terminal::body()->writeln(" 🔑 Sudo password: <info>" . mask($password) . "</info>");
        Terminal::footer()->clear();

        $key = randomize(32);

        self::$replacements["{app.key}"]       = $key;
        self::$replacements["{sudo.password}"] = cypher($password, $key);
    }

    public function getSuccessMessage(): string
    {
        return sprintf("Application %s successfully generated. Happy coding!", self::$replacements["{app.name}"]);
    }

    public function getErrorMessage(): string
    {
        return sprintf("Application %s could not be generated. Please try again.", self::$replacements["{app.name}"]);
    }
}
