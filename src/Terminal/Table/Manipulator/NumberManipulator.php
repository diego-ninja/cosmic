<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Table\Manipulator;

class NumberManipulator implements TableManipulatorInterface
{
    public const TYPE = 'number';

    public function manipulate(mixed $value): ?string
    {
        if (!$value) {
            return '';
        }

        return number_format($value);
    }
}
