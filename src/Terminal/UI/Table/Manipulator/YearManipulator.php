<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Table\Manipulator;

class YearManipulator implements TableManipulatorInterface
{
    final public const TYPE = 'year';

    public function manipulate(mixed $value): ?string
    {
        if (!$value) {
            return 'Not Recorded';
        }

        return date('Y', $value);
    }
}
