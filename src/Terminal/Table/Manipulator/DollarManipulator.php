<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Table\Manipulator;

class DollarManipulator implements TableManipulatorInterface
{
    public const TYPE = 'dollar';

    public function manipulate(mixed $value): ?string
    {
        return '$' . number_format($value, 2);
    }
}
