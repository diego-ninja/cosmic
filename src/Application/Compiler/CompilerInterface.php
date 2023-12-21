<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Application\Compiler;

/**
 * Interface CompilerInterface
 *
 * Represents the interface for application compilers that generate Phar binaries.
 */
interface CompilerInterface
{
    /**
     * Compile the application into a Phar binary.
     *
     * @return bool True if the compilation process is successful, false otherwise.
     */
    public function compile(): bool;
}
