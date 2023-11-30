<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Table\Manipulator;

class TimeManipulator implements TableManipulatorInterface
{
    public const TYPE = 'time';

    public function manipulate(mixed $value): ?string
    {
        if (!$value) {
            return 'Not Recorded';
        }

        return date('g:i a', $value);
    }
}
