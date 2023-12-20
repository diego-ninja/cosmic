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
use Ninja\Cosmic\Environment\Env;
use Ninja\Cosmic\Notifier\NotifiableInterface;
use Ninja\Cosmic\Terminal\Input\Question;
use Ninja\Cosmic\Terminal\Spinner\SpinnerFactory;
use Ninja\Cosmic\Terminal\Table\Column\TableColumn;
use Ninja\Cosmic\Terminal\Table\Table;
use Ninja\Cosmic\Terminal\Table\TableConfig;
use Ninja\Cosmic\Terminal\Terminal;
use Ninja\Cosmic\Terminal\UI\UI;
use Phar;
use ReflectionException;
use Symfony\Component\Process\Process;
use ZipArchive;

use function Cosmic\cypher;
use function Cosmic\git_config;
use function Cosmic\is_phar;
use function Cosmic\mask;
use function Cosmic\randomize;
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
    private static array $summary      = [];

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function __invoke(?string $name, ?string $path): int
    {
        UI::header(" Welcome to the <span class='text-gray700'>cosmic</span> application initializer");

        Terminal::output()->writeln(" This utility will walk you through creating a new <info>cosmic</info> application.");
        Terminal::output()->writeln(" Press <default>^C</default> at any time to quit.");
        Terminal::output()->writeln("");

        $this->askPackageName();
        $this->askApplicationPath();
        $this->askApplicationDescription();
        $this->askApplicationAuthor();
        $this->askApplicationWebsite();
        $this->askApplicationLicense();
        $this->askSudoPassword();

        Terminal::clear(count(self::$summary));
        Terminal::output()->writeln("");
        $this->displaySummary();

        if (Question::confirm("Do you confirm generation of the application?")) {
            Terminal::output()->writeln("");
            Terminal::output()->writeln(
                sprintf(
                    " %s Generating <info>cosmic</info> application",
                    Terminal::getTheme()->getAppIcon()
                )
            );

            $this->executionResult = $this->expandApplication() && $this->replacePlaceholders() && $this->installApplicationDependencies() && $this->renameApplication();

            if ($this->executionResult) {
                Terminal::output()->writeln("");
                Terminal::output()->writeln(
                    sprintf(
                        " %s <info>%s</info> application generated. Happy coding!",
                        Terminal::getTheme()->getAppIcon(),
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

    private function askPackageName(): void
    {
        $default_name = sprintf("%s/cosmic-app", get_current_user());
        $package_name = Question::ask(
            message: sprintf(" %s <question>Package name</question> (vendor/name):", "📦"),
            default: $default_name,
            decorated: false
        );
        [,$binary_name] = explode("/", $package_name);

        self::$summary[] = ["key" => "📦 Package name", "value" => $package_name];
        self::$summary[] = ["key" => "🚀 Binary name", "value" => $binary_name];

        self::$replacements["{app.name}"]     = $binary_name;
        self::$replacements["{app.root}"]     = ucfirst($binary_name);
        self::$replacements["{package.name}"] = $package_name;
    }

    private function askApplicationPath(): void
    {
        $default_path = getcwd();
        $path         = Question::ask(message: " 📁 <question>Application path</question>:", default: $default_path, decorated: false);

        self::$summary[]                  = ["key" => "📁 Application path ", "value" => $path];
        self::$replacements["{app.path}"] = $path;
    }

    private function askApplicationDescription(): void
    {
        $description = Question::ask(message: " 📄 <question>Description</question>:", decorated: false);

        self::$summary[]                         = ["key" => "📄 Description", "value" => $description ?? ""];
        self::$replacements["{app.description}"] = $description;
    }

    private function askApplicationAuthor(): void
    {
        $author = Question::ask(message: " 🥷 <question>Author</question>:", default: git_config("user.name"), decorated: false);
        $email  = Question::ask(message: " 📧 <question>E-Mail</question>:", default: git_config("user.email"), decorated: false);

        self::$summary[] = ["key" => "🥷 Author", "value" => sprintf("%s <%s>", $author, $email)];

        self::$replacements["{author.name}"]  = $author;
        self::$replacements["{author.email}"] = $email;
    }

    private function askApplicationWebsite(): void
    {
        $website = Question::ask(message: " 🌎 <question>Website</question>:", default: git_config("user.website"), decorated: false);

        self::$summary[]                    = ["key" => "🌎 Website", "value" => $website];
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

        self::$summary[]                     = ["key" => "🔎 License", "value" => $license[0]];
        self::$replacements["{app.license}"] = $license[0];
    }

    private function askSudoPassword(): void
    {
        $password = Question::hidden(message: " 🔑 <question>Sudo password</question>:", decorated: false);
        if ($password) {
            $key = randomize(32);

            self::$replacements["{app.key}"]       = $key;
            self::$replacements["{sudo.password}"] = cypher($password, $key);

            self::$summary[] = ["key" => "🔑 Sudo password", "value" => mask($password, 10)];
        }
    }

    private function displaySummary(): void
    {
        $config = new TableConfig();
        $config->setShowHeader(false);
        $config->setPadding(1);

        (new Table(data: self::$summary, columns: [], config: $config))
            ->addColumn(new TableColumn(name: '', key: 'key', color: 'info'))
            ->addColumn((new TableColumn(name: '', key: 'value')))
            ->display(Terminal::output());
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
