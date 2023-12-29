<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Input\Select\Input;

interface SelectInputInterface
{
    public function getMessage(): string;
    /**
     * @return array<string, string>
     */
    public function getOptions(): array;
    /**
     * @return array<string>
     */
    public function getSelections(): array;
    public function hasSelections(): bool;
    public function isSelected(string $option): bool;
    public function select(string $option): void;
    public function deselect(string $option): void;
    public function controlMode(): int;
}
