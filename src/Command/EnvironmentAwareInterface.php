<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command;

interface EnvironmentAwareInterface
{
    public function isAvailableIn(string $environment): bool;
    public function getAvailableEnvironments(): array;
}
