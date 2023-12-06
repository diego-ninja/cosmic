<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Select\Input;

interface SelectInputInterface
{
    public function getMessage(): string;
    public function getOptions(): array;
    public function getSelections(): array;
    public function hasSelections(): bool;
    public function isSelected(string $option): bool;
    public function select(string $option): void;
    public function controlMode(): int;
}
