<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Table\Manipulator;

class DatelongManipulator implements TableManipulatorInterface
{
    public const TYPE = 'datelong';

    public function manipulate(mixed $value): ?string
    {
        if (!$value) {
            return 'Not Recorded';
        }
        return date('d-m-Y H:i:s', $value);
    }
}
