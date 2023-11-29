<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme;

use InvalidArgumentException;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;

use function Termwind\style;

final class ThemeLoader implements ThemeLoaderInterface, ThemeInterface
{
    private ThemeInterface $theme;

    public function __construct(private array $themes, private readonly OutputInterface $output) {}
    public function load(OutputInterface $output): void
    {
        $this->loadColors($output);
        $this->loadStyles($output);
    }

    /**
     * @throws \JsonException
     */
    public function loadDirectory(string $directory): ThemeLoaderInterface
    {
        $themes = [];
        foreach (glob($directory . "/*") as $themeFolder) {
            $theme = Theme::fromThemeFolder($themeFolder);
            $this->addTheme($theme);
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
        $this->load($this->output);

        return $this;
    }

    private function loadColors(OutputInterface $output): void
    {
        foreach ($this->theme->getColors() as $name => $color) {
            if (is_array($color)) {
                style($name)->color($color["fg"] ?? null);
                $color = new OutputFormatterStyle(
                    foreground: $color["fg"]   ?? null,
                    background: $color["bg"]   ?? null,
                    options: $color["options"] ?? null
                );
            } else {
                style($name)->color($color);
                $color = new OutputFormatterStyle($color);
            }

            $output->getFormatter()->setStyle($name, $color);
        }
    }

    private function loadStyles(OutputInterface $output): void
    {
        foreach ($this->theme->getStyles() as $name => $style) {
            style($name)->apply($style);
        }
    }

    public function getLogo(): ?string
    {
        return $this->theme->getLogo();
    }

    public function getIcon(string $iconName): ?string
    {
        return $this->theme->getIcon($iconName);
    }

    public function getAppIcon(): ?string
    {
        return $this->theme->getAppIcon();
    }

    public function getConfig(string $key): mixed
    {
        return $this->theme->getConfig($key);
    }

    public function getColors(): array
    {
        return $this->theme->getColors();
    }

    public function getColor(string $colorName): ?string
    {
        return $this->theme->getColor($colorName);
    }

    public function getStyles(): array
    {
        return $this->theme->getStyles();
    }

    public function getStyle(string $styleName): ?string
    {
        return $this->theme->getStyle($styleName);
    }

    public function getName(): string
    {
        return $this->theme->getName();
    }

    public function jsonSerialize(): array
    {
        return $this->theme->jsonSerialize();
    }

    public function getNotificationIcon(): ?string
    {
        return $this->theme->getNotificationIcon();
    }

    public function setNotificationIcon(string $icon): void
    {
        $this->theme->setNotificationIcon($icon);
    }

    public function setLogo(string $logo): void
    {
        $this->theme->setLogo($logo);
    }
}
