<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Table\Manipulator;

class BoolManipulator implements TableManipulatorInterface
{
    public const TYPE = 'bool';

    public function manipulate(mixed $value): ?string
    {
        if(!is_bool($value)) {
            return $value;
        }

        return $value ? 'true' : 'false';
    }
}
