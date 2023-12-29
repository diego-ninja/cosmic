<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Table\Manipulator;

class TimeManipulator implements TableManipulatorInterface
{
    final public const TYPE = 'time';

    public function manipulate(mixed $value): ?string
    {
        if (!$value) {
            return 'Not Recorded';
        }

        return date('g:i a', $value);
    }
}
