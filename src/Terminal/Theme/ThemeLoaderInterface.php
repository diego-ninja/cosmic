<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme;

use Symfony\Component\Console\Output\OutputInterface;

interface ThemeLoaderInterface
{
    public function load(OutputInterface $output): void;
    public function addTheme(ThemeInterface $theme): self;
    public function enableTheme(string $themeName): self;
    public function getEnabledTheme(): ThemeInterface;
    public function loadDirectory(string $directory): self;

}
