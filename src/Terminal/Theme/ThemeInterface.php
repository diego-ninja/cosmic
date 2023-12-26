<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme;

use JsonSerializable;
use Ninja\Cosmic\Terminal\Theme\Element\Charset\Charset;
use Ninja\Cosmic\Terminal\Theme\Element\Charset\CharsetCollection;
use Ninja\Cosmic\Terminal\Theme\Element\Color\Color;
use Ninja\Cosmic\Terminal\Theme\Element\Color\ColorCollection;
use Ninja\Cosmic\Terminal\Theme\Element\Icon\Icon;
use Ninja\Cosmic\Terminal\Theme\Element\Icon\IconCollection;
use Ninja\Cosmic\Terminal\Theme\Element\Spinner\Spinner;
use Ninja\Cosmic\Terminal\Theme\Element\Spinner\SpinnerCollection;
use Ninja\Cosmic\Terminal\Theme\Element\Style\AbstractStyle;
use Ninja\Cosmic\Terminal\Theme\Element\Style\StyleCollection;
use Symfony\Component\Console\Output\OutputInterface;

interface ThemeInterface extends JsonSerializable
{
    public function load(OutputInterface $output): void;
    public function getName(): string;
    public function getVersion(): string;
    public function getDescription(): ?string;
    public function getLogo(): ?string;
    public function getIcons(): IconCollection;
    public function getIcon(string $iconName): ?Icon;
    public function getAppIcon(): ?Icon;
    public function getConfig(?string $key = null): mixed;
    public function getColors(): ColorCollection;
    public function getColor(string $colorName): ?Color;
    public function getStyles(): StyleCollection;
    public function getStyle(string $styleName): ?AbstractStyle;
    public function getCharsets(): CharsetCollection;
    public function getCharset(string $charsetName): ?Charset;
    public function getSpinners(): SpinnerCollection;
    public function getSpinner(string $spinnerName): ?Spinner;
    public function getNotificationIcon(): ?string;
    public function setNotificationIcon(string $icon): void;
    public function setLogo(string $logo): void;
    public function getParent(): ?ThemeInterface;
    public function setParent(ThemeInterface $theme): void;
}
