<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command;

use Exception;
use Ninja\Cosmic\Application\Application;
use Ninja\Cosmic\Command\Attribute\Alias;
use Ninja\Cosmic\Command\Attribute\Description;
use Ninja\Cosmic\Command\Attribute\Icon;
use Ninja\Cosmic\Command\Attribute\Name;
use Ninja\Cosmic\Command\Attribute\Option;
use Ninja\Cosmic\Command\Attribute\Signature;
use Ninja\Cosmic\Environment\Env;
use Ninja\Cosmic\Event\Lifecycle;
use Ninja\Cosmic\Terminal\Terminal;
use Ninja\Cosmic\Terminal\UI\Spinner\SpinnerFactory;
use Symfony\Component\Process\Process;

use function Cosmic\find_binary;
use function Cosmic\sudo;

#[Icon("🚚")]
#[Name("install")]
#[Description("Install <info>{env.app_name}</info> binary into desired path.")]
#[Signature("install [--path=]")]
#[Option("--path", "The target installation directory path", "/usr/local/bin")]
#[Alias("app:install")]
final class InstallCommand extends CosmicCommand
{
    /**
     * @throws Exception
     */
    public function __invoke(string $path): int
    {
        Terminal::output()->writeln("");
        $this->executionResult = $this->build() && $this->install($path);

        Lifecycle::dispatchLifecycleEvent(
            event_name: Application::LIFECYCLE_APP_INSTALL,
            event_args: ["result" => $this->executionResult, "path" => $path]
        );

        return $this->exit();
    }

    /**
     * @throws Exception
     */
    private function build(): bool
    {
        $command = sprintf(
            "%s %s %s",
            find_binary("php"),
            $_SERVER['argv'][0],
            "build --env local"
        );

        return SpinnerFactory::for(
            callable: (Process::fromShellCommandline($command))->setWorkingDirectory(Env::basePath()),
            message: sprintf(
                'Building <info>%s</info> (<info>%s</info>) binary',
                Env::appName(),
                Env::appVersion()
            )
        );
    }

    /**
     * @throws Exception
     */
    private function install(string $path): bool
    {
        $command = sprintf(
            "mv %s/builds/%s.phar %s/%s",
            Env::basePath(),
            Env::appName(),
            $path,
            Env::appName()
        );

        $command = sudo($command, Env::get("SUDO_PASSWORD"));

        return SpinnerFactory::for(
            callable: (Process::fromShellCommandline($command))->setWorkingDirectory(Env::basePath()),
            message: sprintf(
                'Installing <info>%s</info> (<info>%s</info>) binary into <comment>%s</comment>',
                Env::appName(),
                Env::appVersion(),
                $path
            )
        );
    }

}
