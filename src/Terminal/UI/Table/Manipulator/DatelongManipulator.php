<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Table\Manipulator;

class DatelongManipulator implements TableManipulatorInterface
{
    final public const TYPE = 'datelong';

    public function manipulate(mixed $value): ?string
    {
        if (!$value) {
            return 'Not Recorded';
        }
        return date('d-m-Y H:i:s', $value);
    }
}
