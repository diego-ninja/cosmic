<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Table\Manipulator;

class BoolManipulator implements TableManipulatorInterface
{
    final public const TYPE = 'bool';

    public function manipulate(mixed $value): ?string
    {
        if(!is_bool($value)) {
            return $value;
        }

        return $value ? 'true' : 'false';
    }
}
