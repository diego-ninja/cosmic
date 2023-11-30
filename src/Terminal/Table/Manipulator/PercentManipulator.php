<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Table\Manipulator;

class PercentManipulator implements TableManipulatorInterface
{
    public const TYPE = 'percent';

    public function manipulate(mixed $value): ?string
    {
        if (!$value) {
            return '';
        }

        return number_format($value, 2) . '%';
    }
}
