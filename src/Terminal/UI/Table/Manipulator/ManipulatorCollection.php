<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Table\Manipulator;

use Ramsey\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<TableManipulatorInterface>
 */
class ManipulatorCollection extends AbstractCollection
{
    public function getType(): string
    {
        return TableManipulatorInterface::class;
    }

    public function apply(mixed $value): string
    {
        foreach ($this->data as $manipulator) {
            $value = $manipulator->manipulate($value);
        }

        return $value;
    }
}
