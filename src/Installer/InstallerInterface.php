<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Installer;

use Closure;

/**
 * Interface InstallerInterface
 *
 * Represents an installer interface for managing package installations.
 */
interface InstallerInterface
{
    /**
     * Performs the installation process.
     *
     * @return bool True if the installation was successful, false otherwise.
     */
    public function install(): bool;

    /**
     * Checks if a package is installed.
     *
     * @param string $package The name of the package.
     *
     * @return bool True if the package is installed, false otherwise.
     */
    public function isPackageInstalled(string $package): bool;

    /**
     * Executes a callback before the installation process.
     *
     * @param Closure|null $callback The callback function to execute before installation.
     */
    public function preInstall(?Closure $callback = null): void;

    /**
     * Executes a callback after the installation process.
     *
     * @param Closure|null $callback The callback function to execute after installation.
     */
    public function postInstall(?Closure $callback = null): void;

    /**
     * Adds a package to the installer.
     *
     * @param string      $package The name of the package.
     * @param string|null $version The version constraint for the package (default is "*").
     */
    public function addPackage(string $package, ?string $version = "*"): void;
}
