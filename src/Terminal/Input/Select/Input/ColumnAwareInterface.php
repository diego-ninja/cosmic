<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Input\Select\Input;

interface ColumnAwareInterface
{
    public function hasEntryAt(int $row, int $column): bool;
    public function getEntryAt(int $row, int $column): string;
    public function getColumns(?int $columnSize = null): array;
    public function getColumnCount(): int;

}
