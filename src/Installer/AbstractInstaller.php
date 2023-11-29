<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Installer;

use Closure;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractInstaller implements InstallerInterface
{
    protected array $packages        = [];
    protected ?Closure $pre_install  = null;
    protected ?Closure $post_install = null;

    protected bool $is_installed = false;

    public function __construct(protected OutputInterface $output) {}

    public function preInstall(?Closure $callback = null): void
    {
        $this->pre_install = $callback;
    }

    public function postInstall(?Closure $callback = null): void
    {
        $this->post_install = $callback;
    }

    public function isInstalled(): bool
    {
        return $this->is_installed;
    }

    public function addPackage(string $package, ?string $version = "*"): void
    {
        $this->packages[$package] = $version;
    }

}
