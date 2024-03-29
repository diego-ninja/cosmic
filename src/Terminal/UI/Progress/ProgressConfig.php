<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Progress;

use Ninja\Cosmic\Terminal\Terminal;
use PHLAK\Config\Config;

class ProgressConfig extends Config
{
    final public const DEFAULT_BAR_COLOR      = 'cyan';
    final public const DEFAULT_TEXT_COLOR     = 'white';
    final public const DEFAULT_APPLY_GRADIENT = false;
    final public const DEFAULT_USE_SEGMENTS   = false;
    final public const DEFAULT_CHAR_EMPTY     = '░';
    final public const DEFAULT_CHAR_FULL      = '█';
    final public const DEFAULT_WIDTH          = 40;
    final public const DEFAULT_FORMAT         = "{detail}{nl}{bar} {percentage}% {steps}";

    /**
     * @param array<string, mixed>|string|null $context
     * @param string|null $prefix
     */
    public function __construct(array|string $context = null, string $prefix = null)
    {
        if (is_array($context)) {
            $context = array_merge($this->getDefaultConfig(), $context);
        }

        parent::__construct($context, $prefix);
    }

    public function getBarColor(): string
    {
        return $this->get('bar_color', self::DEFAULT_BAR_COLOR);
    }

    public function setBarColor(string $barColor): self
    {
        $this->set('bar_color', $barColor);
        return $this;
    }

    public function getTextColor(): string
    {
        return $this->get('text_color', self::DEFAULT_TEXT_COLOR);
    }

    public function setTextColor(string $textColor): self
    {
        $this->set('text_color', $textColor);
        return $this;
    }

    public function getApplyGradient(): bool
    {
        return $this->get('apply_gradient', self::DEFAULT_APPLY_GRADIENT);
    }

    public function setApplyGradient(bool $applyGradient): self
    {
        $this->set('apply_gradient', $applyGradient);
        return $this;
    }

    public function getUseSegments(): bool
    {
        return $this->get('use_segments', self::DEFAULT_USE_SEGMENTS);
    }

    public function setUseSegments(bool $useSegments): self
    {
        $this->set('use_segments', $useSegments);
        return $this;
    }

    public function getCharEmpty(): string
    {
        return $this->get('char_empty', self::DEFAULT_CHAR_EMPTY);
    }

    public function setCharEmpty(string $charEmpty): self
    {
        $this->set('char_empty', $charEmpty);
        return $this;
    }

    public function getCharFull(): string
    {
        return $this->get('char_full', self::DEFAULT_CHAR_FULL);
    }

    public function setCharFull(string $charFull): self
    {
        $this->set('char_full', $charFull);
        return $this;
    }

    public function getWidth(): int
    {
        return $this->get('width', self::DEFAULT_WIDTH);
    }

    public function setWidth(int $width): self
    {
        $this->set('width', $width);
        return $this;
    }

    public function getFormat(): string
    {
        return $this->get('format', self::DEFAULT_FORMAT);
    }

    public function setFormat(string $format): self
    {
        $this->set('format', $format);
        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    private function getDefaultConfig(): array
    {
        return [
            "bar_color"      => self::DEFAULT_BAR_COLOR,
            "text_color"     => self::DEFAULT_TEXT_COLOR,
            "apply_gradient" => self::DEFAULT_APPLY_GRADIENT,
            "use_segments"   => self::DEFAULT_USE_SEGMENTS,
            "char_empty"     => self::DEFAULT_CHAR_EMPTY,
            "char_full"      => self::DEFAULT_CHAR_FULL,
            "width"          => self::DEFAULT_WIDTH,
            "format"         => self::DEFAULT_FORMAT,
            "spacing"        => Terminal::getTheme()?->getConfig("spacing"),
        ];
    }

}
