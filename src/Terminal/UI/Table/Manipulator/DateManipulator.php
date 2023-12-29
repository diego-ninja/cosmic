<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Table\Manipulator;

class DateManipulator implements TableManipulatorInterface
{
    final public const TYPE = 'date';

    public function manipulate(mixed $value): ?string
    {
        if (!$value) {
            return 'Not Recorded';
        }
        return date('d-m-Y', $value);
    }
}
