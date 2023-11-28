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
    ) {
    }

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
            colors: $data["colors"],
            styles: $data["styles"],
            icons: $data["icons"],
            config: $data["config"],
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
        $theme_file = sprintf("%s/theme.json", $folder);
        $logo_file  = sprintf("%s/logo.php", $folder);
        $notification_file = sprintf("%s/notification.png", $folder);

        if (file_exists($theme_file)) {
            $theme = self::fromFile($theme_file);
            $theme->logo = file_exists($logo_file) ? require $logo_file : null;
            $theme->notification = file_exists($notification_file) ? $notification_file : null;
            return $theme;
        }


        throw new RuntimeException("Theme '$folder' not found.");
    }

    public function toArray(): array
    {
        return [
            "name"    => $this->name,
            "colors"  => $this->colors,
            "styles"  => $this->styles,
            "icons"   => $this->icons,
            "config"  => $this->config,
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

    public function getIcon(string $iconName): ?string
    {
        return $this->icons[$iconName] ?? null;
    }

    public function getAppIcon(): ?string
    {
        return $this->icons["application"] ?? null;
    }

    public function getConfig(string $key): mixed
    {
        return $this->config[$key] ?? null;
    }

    public function getColors(): array
    {
        return $this->colors;
    }

    public function getColor(string $colorName): ?string
    {
        return $this->colors[$colorName] ?? null;
    }

    public function getStyles(): array
    {
        return $this->styles;
    }

    public function getStyle(string $styleName): ?string
    {
        return $this->styles[$styleName] ?? null;
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
}
