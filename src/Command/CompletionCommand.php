<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command;

use DirectoryIterator;
use Ninja\Cosmic\Command\Attribute\Argument;
use Ninja\Cosmic\Command\Attribute\Decorated;
use Ninja\Cosmic\Command\Attribute\Description;
use Ninja\Cosmic\Command\Attribute\Icon;
use Ninja\Cosmic\Command\Attribute\Name;
use Ninja\Cosmic\Command\Attribute\Signature;
use Ninja\Cosmic\Terminal\Terminal;
use Symfony\Component\Console\Command\CompleteCommand;

#[Icon("🐚")]
#[Name("completion")]
#[Description('Dump the shell completion script. <comment>[default: "{env.shell}"]</comment>')]
#[Signature("completion [shell]")]
#[Argument("shell", 'The shell type (e.g. "bash"), the value of the "$SHELL" env var will be used if this is not given')] //phpcs:ignore
#[Decorated(false)]
final class CompletionCommand extends CosmicCommand
{
    /**
     * @var string[]
     */
    private array $supported_shells = [];

    public function __invoke(?string $shell): int
    {
        $command_name = basename((string)$_SERVER['argv'][0]);
        $shell ??= $this->guessShell();

        $completion_file = __DIR__ . '/../../resources/completion/completion.' . $shell;
        if (!file_exists($completion_file)) {
            $supported_shells = $this->getSupportedShells();

            if ($shell !== '' && $shell !== '0') {
                Terminal::output()
                    ->writeln(
                        sprintf(
                            '<error>Detected shell "%s", which is not supported by shell completion (supported shells: "%s").</>', //phpcs:ignore
                            $shell,
                            implode('", "', $supported_shells)
                        )
                    );
            } else {
                Terminal::output()
                    ->writeln(
                        sprintf(
                            '<error>Shell not detected, shell completion only supports "%s").</>', //phpcs:ignore
                            implode('", "', $supported_shells)
                        )
                    );
            }

            return $this->failure();
        }

        $content = file_get_contents($completion_file);
        if ($content === false) {
            Terminal::output()->writeln('<error>Failed to read completion file.</>');

            return $this->failure();
        }

        Terminal::output()->write(
            str_replace(
                ['{{ COMMAND_NAME }}', '{{ VERSION }}'],
                [$command_name, CompleteCommand::COMPLETION_API_VERSION],
                $content
            )
        );

        return $this->success();
    }

    public function getCommandHelp(): ?string
    {
        $fullCommand = $_SERVER['PHP_SELF'];
        $commandName = basename((string)$fullCommand);
        $fullCommand = @realpath($fullCommand) ?: $fullCommand;

        $shell                     = $this->guessShell();
        [$rcFile, $completionFile] = match ($shell) {
            'fish'  => ['~/.config/fish/config.fish', "/etc/fish/completions/$commandName.fish"],
            'zsh'   => ['~/.zshrc', '$fpath[1]/_' . $commandName],
            default => ['~/.bashrc', "/etc/bash_completion.d/$commandName"],
        };

        $supportedShells = implode(', ', $this->getSupportedShells());

        return <<<EOH
        The <info>%command.name%</> command dumps the shell completion script required
        to use shell autocompletion (currently, {$supportedShells} completion are supported).

        <comment>Static installation
        -------------------</>

        Dump the script to a global completion file and restart your shell:

            <info>%command.full_name% {$shell} | sudo tee {$completionFile}</>

        Or dump the script to a local file and source it:

            <info>%command.full_name% {$shell} > completion.sh</>

            <comment># source the file whenever you use the project</>
            <info>source completion.sh</>

            <comment># or add this line at the end of your "{$rcFile}" file:</>
            <info>source /path/to/completion.sh</>

        <comment>Dynamic installation
        --------------------</>

        Add this to the end of your shell configuration file (e.g. <info>"{$rcFile}"</>):

            <info>eval "$({$fullCommand} completion {$shell})"</>
        EOH;
    }

    /**
     * @return string[]
     */
    private function getSupportedShells(): array
    {
        if ($this->supported_shells !== []) {
            return $this->supported_shells;
        }

        $shells = [];

        foreach (new DirectoryIterator(__DIR__ . '/../../resources/completion') as $file) {
            if ($file->isFile() && str_starts_with($file->getBasename(), 'completion.')) {
                $shells[] = $file->getExtension();
            }
        }
        sort($shells);

        return $this->supported_shells = $shells;
    }

    private function guessShell(): string
    {
        return basename($_SERVER['SHELL'] ?? '');
    }
}
