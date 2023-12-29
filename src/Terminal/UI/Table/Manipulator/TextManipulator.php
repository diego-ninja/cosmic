<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Table\Manipulator;

class TextManipulator implements TableManipulatorInterface
{
    final public const TYPE = 'text';

    public function manipulate(mixed $value): ?string
    {
        if (!$value) {
            return '';
        }

        return strip_tags((string)$value);
    }
}
