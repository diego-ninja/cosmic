<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme;

use Symfony\Component\Console\Output\OutputInterface;

interface ThemeLoaderInterface
{
    public function load(OutputInterface $output): void;
    public function addTheme(ThemeInterface $theme): void;
    public function enableTheme(string $themeName): void;
    public function getEnabledTheme(): ThemeInterface;
}
