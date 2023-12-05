<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command;

use DirectoryIterator;
use Ninja\Cosmic\Command\Attribute\Decorated;
use Ninja\Cosmic\Command\Attribute\Description;
use Ninja\Cosmic\Command\Attribute\Icon;
use Ninja\Cosmic\Command\Attribute\Name;
use Ninja\Cosmic\Command\Attribute\Option;
use Ninja\Cosmic\Command\Attribute\Signature;
use Ninja\Cosmic\Terminal\Terminal;
use Symfony\Component\Console\Command\CompleteCommand;

#[Icon("🐚")]
#[Name("completion")]
#[Description('Dump the shell completion script. <comment>[default: "{env.shell}"]</comment>')]
#[Signature("completion [shell]")]
#[Option("shell", 'The shell type (e.g. "bash"), the value of the "$SHELL" env var will be used if this is not given')] //phpcs:ignore
#[Decorated(false)]
final class CompletionCommand extends CosmicCommand
{
    private array $supported_shells = [];

    public function __invoke(?string $shell): int
    {
        $command_name = basename($_SERVER['argv'][0]);
        $shell        = $shell ?? self::guessShell();

        $completion_file = __DIR__ . '/../../resources/completion/completion.' . $shell;
        if (!file_exists($completion_file)) {
            $supported_shells = $this->getSupportedShells();

            if ($shell) {
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

        Terminal::output()->write(
            str_replace(
                ['{{ COMMAND_NAME }}', '{{ VERSION }}'],
                [$command_name, CompleteCommand::COMPLETION_API_VERSION],
                file_get_contents($completion_file)
            )
        );

        return $this->success();
    }

    private function getSupportedShells(): array
    {
        if (!empty($this->supported_shells)) {
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

    private static function guessShell(): string
    {
        return basename($_SERVER['SHELL'] ?? '');
    }
}
