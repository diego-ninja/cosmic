<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Input\Select\Input;

interface ColumnAwareInterface
{
    public function hasEntryAt(int $row, int $column): bool;
    public function getEntryAt(int $row, int $column): string;
    /**
     * @return array<int<1,max>, list<string>>
     */
    public function getColumns(?int $columnSize = null): array;
    public function getColumnCount(): int;

}
