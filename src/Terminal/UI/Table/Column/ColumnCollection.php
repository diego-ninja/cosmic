<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Table\Column;

use Ramsey\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<TableColumn>
 */
class ColumnCollection extends AbstractCollection
{
    public function getType(): string
    {
        return TableColumn::class;
    }

    public function getByKey(string $key): ?TableColumn
    {
        foreach ($this->getIterator() as $item) {
            if ($item->key === $key) {
                return $item;
            }
        }

        return null;
    }

    public function getByName(string $name): ?TableColumn
    {
        foreach ($this->getIterator() as $item) {
            if ($item->name === $name) {
                return $item;
            }
        }

        return null;
    }

}
