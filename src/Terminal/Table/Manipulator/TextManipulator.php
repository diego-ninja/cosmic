<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Table\Manipulator;

class TextManipulator implements TableManipulatorInterface
{
    public const TYPE = 'text';

    public function manipulate(mixed $value): ?string
    {
        if (!$value) {
            return '';
        }

        return strip_tags((string)$value);
    }
}
