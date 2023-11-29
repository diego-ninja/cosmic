<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Installer;

use Closure;

interface InstallerInterface
{
    public function install(): bool;
    public function isPackageInstalled(string $package): bool;
    public function preInstall(?Closure $callback = null): void;
    public function postInstall(?Closure $callback = null): void;
    public function addPackage(string $package, ?string $version = "*"): void;
}
