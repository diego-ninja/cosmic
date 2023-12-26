<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Table\Manipulator;

class DatetimeManipulator implements TableManipulatorInterface
{
    public const TYPE = 'datetime';

    public function manipulate(mixed $value): ?string
    {
        if (!$value) {
            return 'Not Recorded';
        }

        return date('jS F Y, g:i a', $value);
    }
}
