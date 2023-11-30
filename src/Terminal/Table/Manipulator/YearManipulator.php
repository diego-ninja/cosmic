<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Table\Manipulator;

class YearManipulator implements TableManipulatorInterface
{
    public const TYPE = 'year';

    public function manipulate(mixed $value): ?string
    {
        if (!$value) {
            return 'Not Recorded';
        }

        return date('Y', $value);
    }
}
