<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command;

use Ninja\Cosmic\Application\Application;

interface CommandInterface
{
    public const LIFECYCLE_COMMAND_RUN     = 'command.run';
    public const LIFECYCLE_COMMAND_SUCCESS = 'command.success';
    public const LIFECYCLE_COMMAND_FAILURE = 'command.failure';
    public const LIFECYCLE_COMMAND_ERROR   = 'command.error';

    public function getCommandName(): string;
    public function getSignature(): string;
    public function getCommandDescription(): string;
    public function getArgumentDescriptions(): array;
    public function getDefaults(): array;
    public function getCommandIcon(): string;
    public function getCommandHelp(): ?string;
    public function getAliases(): array;
    public function isHidden(): bool;
    public function register(Application $app): void;
    public function isDecorated(): bool;
    public function getApplication(): Application;
    public function setApplication(Application $app): void;
}
