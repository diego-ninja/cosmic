<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Table\Manipulator;

class DollarManipulator implements TableManipulatorInterface
{
    final public const TYPE = 'dollar';

    public function manipulate(mixed $value): ?string
    {
        return '$' . number_format($value, 2);
    }
}
