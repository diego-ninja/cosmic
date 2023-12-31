<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Input\Select\Input\Trait;

use InvalidArgumentException;
use Ninja\Cosmic\Terminal\UI\Input\Select\Input\Exception\IndexOutOfRangeException;

trait ColumnableOptionTrait
{
    public const DEFAULT_COLUMN_SIZE = 3;

    /** @var array<int, list<string>> */
    protected array $columns;

    protected int $columnSize = self::DEFAULT_COLUMN_SIZE;

    /**
     * @return array<int, list<string>>
     */
    public function getColumns(int $columnSize = null): array
    {
        if (!is_null($columnSize)) {
            $this->columnSize = $columnSize;
        }

        if (!isset($this->columns)) {
            if ($this->columnSize < 1) {
                throw new InvalidArgumentException("Column size must be greater than 0");
            }

            $this->columns = array_chunk($this->getOptions(), $this->columnSize);
        }

        return $this->columns;
    }

    /**
     * @return array<string>
     */
    public function getColumnAt(int $index): array
    {
        if (!empty($this->getColumns()[$index])) {
            return $this->getColumns()[$index];
        }

        throw IndexOutOfRangeException::withIndex((string)$index);
    }

    public function getColumnCount(): int
    {
        return count($this->getColumns());
    }

    public function hasEntryAt(int $rowIndex, int $colIndex): bool
    {
        $chunks = $this->getColumns();
        return array_key_exists($rowIndex, $chunks) && array_key_exists($colIndex, $chunks[$rowIndex]);
    }

    public function getEntryAt(int $rowIndex, int $colIndex): string
    {
        if ($this->hasEntryAt($rowIndex, $colIndex)) {
            return $this->getColumns()[$rowIndex][$colIndex];
        }

        throw IndexOutOfRangeException::withIndex("$rowIndex:$colIndex");
    }

}
