<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme;

use JsonSerializable;

interface ThemeInterface extends JsonSerializable
{
    public function getName(): string;
    public function getLogo(): ?string;
    public function getIcon(string $iconName): ?string;
    public function getAppIcon(): ?string;
    public function getConfig(string $key): mixed;
    public function getColors(): array;
    public function getColor(string $colorName): ?string;
    public function getStyles(): array;
    public function getStyle(string $styleName): ?string;
    public function getNotificationIcon(): ?string;
    public function setNotificationIcon(string $icon): void;
    public function setLogo(string $logo): void;
}
