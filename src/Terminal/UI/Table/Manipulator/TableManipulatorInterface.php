<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Table\Manipulator;

interface TableManipulatorInterface
{
    public function manipulate(mixed $value): ?string;
}
