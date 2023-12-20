<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme;

interface ThemeLoaderInterface
{
    public function addTheme(ThemeInterface $theme): self;
    public function enableTheme(string $themeName): self;
    public function getEnabledTheme(): ThemeInterface;
    public function loadDirectory(string $directory): self;

}
