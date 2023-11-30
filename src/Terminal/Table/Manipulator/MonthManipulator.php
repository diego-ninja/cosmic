<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Table\Manipulator;

class MonthManipulator implements TableManipulatorInterface
{
    public const TYPE = 'month';

    public function manipulate(mixed $value): ?string
    {
        if (!$value) {
            return 'Not Recorded';
        }

        return date('F', $value);
    }
}
