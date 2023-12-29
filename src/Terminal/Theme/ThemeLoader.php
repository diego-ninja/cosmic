<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme;

use DirectoryIterator;
use InvalidArgumentException;
use JsonException;
use Ninja\Cosmic\Exception\BinaryNotFoundException;
use Symfony\Component\Console\Output\OutputInterface;

final class ThemeLoader implements ThemeLoaderInterface
{
    private ThemeInterface $theme;

    /**
     * @param array<string, ThemeInterface> $themes
     */
    public function __construct(private array $themes, private readonly OutputInterface $output) {}
    /**
     * @throws JsonException
     * @throws BinaryNotFoundException
     */
    public function loadDirectory(string $directory): ThemeLoaderInterface
    {
        foreach (new DirectoryIterator($directory) as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }

            if ($fileInfo->isFile() && $fileInfo->getExtension() === "zth") {
                $theme = Theme::fromZippedTheme($fileInfo->getPathname());
                $this->addTheme($theme);
            }

            if ($fileInfo->isDir()) {
                $theme = Theme::fromThemeFolder($fileInfo->getPathname());
                $this->addTheme($theme);
            }

        }

        return $this;
    }

    public function addTheme(ThemeInterface $theme): self
    {
        if (!isset($this->themes[$theme->getName()])) {
            $this->themes[$theme->getName()] = $theme;
        }

        return $this;
    }

    public function getEnabledTheme(): ThemeInterface
    {
        return $this->theme;
    }

    public function enableTheme(string $themeName): self
    {
        if (!isset($this->themes[$themeName])) {
            throw new InvalidArgumentException(sprintf("Theme %s not found", $themeName));
        }

        $this->theme = $this->themes[$themeName];
        $this->theme->load($this->output);

        return $this;
    }
}
