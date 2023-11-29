<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Application\Compiler;

interface CompilerInterface
{
    public function compile(): bool;
}
