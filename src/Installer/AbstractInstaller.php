<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Installer;

use Closure;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AbstractInstaller
 *
 * An abstract base class implementing the InstallerInterface with common functionality.
 */
abstract class AbstractInstaller implements InstallerInterface
{
    /**
     * The list of packages to be installed.
     *
     * @var array<string, string|null>
     */
    protected array $packages = [];

    /**
     * The callback function to execute before installation.
     */
    protected ?Closure $pre_install = null;

    /**
     * The callback function to execute after installation.
     */
    protected ?Closure $post_install = null;

    /**
     * Indicates whether the installation process is completed.
     */
    protected bool $is_installed = false;

    /**
     * AbstractInstaller constructor.
     *
     * @param OutputInterface $output The output interface.
     */
    public function __construct(protected OutputInterface $output) {}

    /**
     * Sets the callback function to execute before installation.
     *
     * @param Closure|null $callback The callback function to execute before installation.
     */
    public function preInstall(?Closure $callback = null): void
    {
        $this->pre_install = $callback;
    }

    /**
     * Sets the callback function to execute after installation.
     *
     * @param Closure|null $callback The callback function to execute after installation.
     */
    public function postInstall(?Closure $callback = null): void
    {
        $this->post_install = $callback;
    }

    /**
     * Checks if the installation process is completed.
     *
     * @return bool True if the installation is completed, false otherwise.
     */
    public function isInstalled(): bool
    {
        return $this->is_installed;
    }

    /**
     * Adds a package to the list of packages to be installed.
     *
     * @param string      $package The name of the package.
     * @param string|null $version The version constraint for the package (default is "*").
     */
    public function addPackage(string $package, ?string $version = "*"): void
    {
        $this->packages[$package] = $version;
    }
}
