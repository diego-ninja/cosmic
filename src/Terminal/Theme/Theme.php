<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme;

use JsonException;
use RuntimeException;

class Theme implements ThemeInterface
{
    public function __construct(
        private readonly string $name,
        private readonly array $colors,
        private readonly array $styles,
        private readonly array $icons,
        private readonly array $config,
        private ?string $logo = null,
        private ?string $notification = null,
        private ?ThemeInterface $parent = null
    ) {}

    /**
     * @throws JsonException
     */
    public static function fromJson(string $json): self
    {
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data["name"],
            colors: $data["colors"] ?? [],
            styles: $data["styles"] ?? [],
            icons: $data["icons"]   ?? [],
            config: $data["config"] ?? []
        );
    }

    /**
     * @throws JsonException
     */
    public static function fromFile(string $filename): self
    {
        return self::fromJson(file_get_contents($filename));
    }

    /**
     * @throws JsonException
     */
    public static function fromThemeFolder(string $folder): self
    {
        $theme_file        = sprintf("%s/theme.json", $folder);
        $logo_file         = sprintf("%s/logo.php", $folder);
        $notification_file = sprintf("%s/notification.png", $folder);

        if (file_exists($theme_file)) {
            $theme               = self::fromFile($theme_file);
            $theme->logo         = file_exists($logo_file) ? require $logo_file : null;
            $theme->notification = file_exists($notification_file) ? $notification_file : null;

            $parent_theme = json_decode(file_get_contents($theme_file), true, JSON_THROW_ON_ERROR, JSON_THROW_ON_ERROR)["extends"] ?? null;
            if ($parent_theme) {
                $theme->parent = self::fromThemeFolder(sprintf("%s/../%s", $folder, $parent_theme));
            }

            return $theme;
        }

        throw new RuntimeException("Theme '$folder' not found.");
    }

    public function toArray(): array
    {
        return [
            "name"   => $this->getName(),
            "colors" => $this->getColors(),
            "styles" => $this->getStyles(),
            "icons"  => $this->getIcons(),
            "config" => $this->getConfig(),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function getIcons(): array
    {
        if ($this->parent) {
            return array_merge($this->parent->getIcons(), $this->icons);
        }

        return $this->icons;
    }

    public function getIcon(string $iconName): ?string
    {
        $icons = $this->getIcons();
        return $icons[$iconName] ?? null;
    }

    public function getAppIcon(): ?string
    {
        $icons = $this->getIcons();
        return $icons["application"] ?? null;
    }

    public function getConfig(?string $key = null): mixed
    {
        if ($this->parent) {
            $config = array_merge($this->parent->getConfig(), $this->config);
        } else {
            $config = $this->config;
        }

        return $key ? $config[$key] : $config;
    }

    public function getColors(): array
    {
        if ($this->parent) {
            return array_merge($this->parent->getColors(), $this->colors);
        }

        return $this->colors;
    }

    public function getColor(string $colorName): ?string
    {
        $colors = $this->getColors();
        return $colors[$colorName] ?? null;
    }

    public function getStyles(): array
    {
        if ($this->parent) {
            return array_merge($this->parent->getStyles(), $this->styles);
        }

        return $this->styles;
    }

    public function getStyle(string $styleName): ?string
    {
        $styles = $this->getStyles();
        return $styles[$styleName] ?? null;
    }

    public function getNotificationIcon(): ?string
    {
        return $this->notification;
    }

    public function setNotificationIcon(string $icon): void
    {
        $this->notification = $icon;
    }

    public function setLogo(string $logo): void
    {
        $this->logo = $logo;
    }
    public function getParent(): ?ThemeInterface
    {
        return $this->parent;
    }

    public function setParent(ThemeInterface $theme): void
    {
        $this->parent = $theme;
    }
}
