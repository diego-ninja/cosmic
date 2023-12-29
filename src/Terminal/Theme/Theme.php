<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme;

use JsonException;
use Ninja\Cosmic\Exception\BinaryNotFoundException;
use Ninja\Cosmic\Terminal\Theme\Element\Charset\Charset;
use Ninja\Cosmic\Terminal\Theme\Element\Charset\CharsetCollection;
use Ninja\Cosmic\Terminal\Theme\Element\CollectionFactory;
use Ninja\Cosmic\Terminal\Theme\Element\Color\Color;
use Ninja\Cosmic\Terminal\Theme\Element\Color\ColorCollection;
use Ninja\Cosmic\Terminal\Theme\Element\Icon\Icon;
use Ninja\Cosmic\Terminal\Theme\Element\Icon\IconCollection;
use Ninja\Cosmic\Terminal\Theme\Element\Spinner\Spinner;
use Ninja\Cosmic\Terminal\Theme\Element\Spinner\SpinnerCollection;
use Ninja\Cosmic\Terminal\Theme\Element\Style\AbstractStyle;
use Ninja\Cosmic\Terminal\Theme\Element\Style\StyleCollection;
use RuntimeException;
use Symfony\Component\Console\Output\OutputInterface;

use function Cosmic\unzip;

class Theme implements ThemeInterface
{
    final public const THEME_SECTION_COLORS   = "colors";
    final public const THEME_SECTION_CHARSETS = "charsets";
    final public const THEME_SECTION_STYLES   = "styles";
    final public const THEME_SECTION_ICONS    = "icons";
    final public const THEME_SECTION_SPINNERS = "spinners";

    /**
     * @var string[] $sections
     */
    private static array $sections = [
        self::THEME_SECTION_COLORS,
        self::THEME_SECTION_CHARSETS,
        self::THEME_SECTION_STYLES,
        self::THEME_SECTION_ICONS,
        self::THEME_SECTION_SPINNERS,
    ];

    /**
     * @param string $name
     * @param string $version
     * @param ColorCollection $colors
     * @param StyleCollection $styles
     * @param IconCollection $icons
     * @param CharsetCollection $charsets
     * @param SpinnerCollection $spinners
     * @param string[] $config
     * @param string|null $description
     * @param string|null $logo
     * @param string|null $notification
     * @param ThemeInterface|null $parent
     */
    public function __construct(
        private readonly string $name,
        private readonly string $version,
        private ColorCollection $colors,
        private StyleCollection $styles,
        private IconCollection $icons,
        private CharsetCollection $charsets,
        private SpinnerCollection $spinners,
        private array $config,
        private readonly ?string $description = null,
        private ?string $logo = null,
        private ?string $notification = null,
        private ?ThemeInterface $parent = null
    ) {}

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

            foreach (self::$sections as $section) {
                $filename = sprintf("%s/%s.json", $folder, $section);
                if (file_exists($filename)) {
                    $theme->loadFile($filename);
                }
            }

            $content = file_get_contents($theme_file);
            if (false === $content) {
                throw new RuntimeException(sprintf("Unable to load theme contents for %s", $folder));
            }

            $parent_theme = json_decode($content, true, JSON_THROW_ON_ERROR, JSON_THROW_ON_ERROR)["extends"] ?? null;
            if ($parent_theme) {
                $theme->parent = self::fromThemeFolder(sprintf("%s/../%s", $folder, $parent_theme));
            }

            return $theme;
        }

        throw new RuntimeException("Theme '$folder' not found.");
    }

    /**
     * @throws JsonException
     */
    public function loadFile(string $filename): void
    {
        $content = file_get_contents($filename);
        if (false === $content) {
            throw new RuntimeException(sprintf("Unable to load theme contents for %s", $filename));
        }

        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        $what = array_keys($data)[0];

        $this->{$what} = CollectionFactory::loadFile($filename);
    }

    public function load(OutputInterface $output): void
    {
        $this->colors->load($output);
        $this->styles->load($output);
    }

    /**
     * @throws JsonException
     */
    public static function fromFile(string $filename): self
    {
        $content = file_get_contents($filename);
        if (false === $content) {
            throw new RuntimeException(sprintf("Unable to load theme contents for %s", $filename));
        }

        return self::fromJson($content);
    }

    /**
     * @throws JsonException
     * @throws BinaryNotFoundException
     */
    public static function fromZippedTheme(string $filename): self
    {
        $content = file_get_contents($filename);
        if (false === $content) {
            throw new RuntimeException(sprintf("Unable to load theme contents for %s", $filename));
        }

        $fingerprint  = md5($content);
        $themeTempDir = sprintf("%s/.cosmic/themes/%s", sys_get_temp_dir(), $fingerprint);

        if (is_dir($themeTempDir)) {
            return self::fromThemeFolder($themeTempDir);
        }

        if (!mkdir($themeTempDir, 0777, true) && !is_dir($themeTempDir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $themeTempDir));
        }

        unzip($filename, $themeTempDir);
        return self::fromThemeFolder($themeTempDir);

    }

    /**
     * @throws JsonException
     */
    public static function fromJson(string $json): self
    {
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        return self::fromArray($data);
    }

    /**
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data["name"],
            version: $data["version"],
            colors: ColorCollection::fromArray($data["colors"] ?? []),
            styles: StyleCollection::fromArray($data["styles"] ?? []),
            icons: IconCollection::fromArray($data["icons"] ?? []),
            charsets: CharsetCollection::fromArray($data["charsets"] ?? []),
            spinners: SpinnerCollection::fromArray($data["spinners"] ?? []),
            config: $data["config"] ?? [],
            description: $data["description"]
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }


    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            "name"        => $this->name,
            "version"     => $this->version,
            "description" => $this->description,
            "colors"      => $this->getColors()->toArray(),
            "styles"      => $this->getStyles()->toArray(),
            "icons"       => $this->getIcons()->toArray(),
            "charsets"    => $this->getCharsets()->toArray(),
            "spinners"    => $this->getSpinners()->toArray(),
            "config"      => $this->getConfig(),
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function getIcons(): IconCollection
    {
        if ($this->parent instanceof ThemeInterface) {
            /** @var IconCollection $icons */
            $icons = $this->parent->getIcons()->merge($this->icons);
            return $icons;
        }

        return $this->icons;
    }

    public function getIcon(string $iconName): ?Icon
    {
        return $this->getIcons()->icon($iconName);
    }

    public function getAppIcon(): ?Icon
    {
        return $this->icons->icon("application");
    }

    public function getConfig(?string $key = null): mixed
    {
        if ($key) {
            return $this->config[$key] ?? null;
        }

        return $this->config;
    }

    public function getColors(): ColorCollection
    {
        if ($this->parent instanceof ThemeInterface) {
            /** @var ColorCollection $colors */
            $colors = $this->parent->getColors()->merge($this->colors);
            return $colors;
        }

        return $this->colors;
    }

    public function getColor(string $colorName): ?Color
    {
        return $this->getColors()->color($colorName);
    }

    public function getStyles(): StyleCollection
    {
        if ($this->parent instanceof ThemeInterface) {
            /** @var StyleCollection $styles */
            $styles = $this->parent->getStyles()->merge($this->styles);
            return $styles;
        }

        return $this->styles;
    }

    public function getStyle(string $styleName): ?AbstractStyle
    {
        return $this->getStyles()->style($styleName);
    }

    public function getCharsets(): CharsetCollection
    {
        if ($this->parent instanceof ThemeInterface) {
            /** @var CharsetCollection $charsets */
            $charsets = $this->parent->getCharsets()->merge($this->charsets);
            return $charsets;
        }

        return $this->charsets;
    }

    public function getCharset(string $charsetName): ?Charset
    {
        return $this->getCharsets()->charset($charsetName);
    }

    public function getSpinners(): SpinnerCollection
    {
        if ($this->parent instanceof ThemeInterface) {
            /** @var SpinnerCollection $spinners */
            $spinners = $this->parent->getSpinners()->merge($this->spinners);
            return $spinners;
        }

        return $this->spinners;
    }

    public function getSpinner(string $spinnerName): ?Spinner
    {
        return $this->getSpinners()->spinner($spinnerName);
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
