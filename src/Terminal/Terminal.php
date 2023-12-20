<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal;

use JsonException;
use Ninja\Cosmic\Terminal\Theme\ThemeInterface;
use Ninja\Cosmic\Terminal\Theme\ThemeLoader;
use Ninja\Cosmic\Terminal\Theme\ThemeLoaderInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyleInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\StreamableInputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

use function Termwind\terminal;

final class Terminal
{
    private static ?self $instance = null;

    private static ThemeLoaderInterface $themeLoader;

    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self(
                new ConsoleOutput(),
                new ArgvInput()
            );
        }
        return self::$instance;
    }

    public static function withTheme(ThemeInterface $theme): self
    {
        self::getInstance()->addTheme($theme);
        self::getInstance()->enableTheme($theme->getName());
        return self::getInstance();
    }

    /**
     * @throws JsonException
     */
    public static function loadThemes(string $directory): self
    {
        $instance = self::getInstance();
        self::$themeLoader->loadDirectory($directory);

        return $instance;
    }

    public static function addTheme(ThemeInterface $theme): self
    {
        self::$themeLoader->addTheme($theme);
        return self::getInstance();
    }

    public static function enableTheme(string $themeName): self
    {
        self::$themeLoader->enableTheme($themeName);
        return self::getInstance();
    }

    public static function getTheme(): ThemeInterface
    {
        return self::$themeLoader->getEnabledTheme();
    }

    public static function output(): ConsoleOutput
    {
        return self::getInstance()->output;
    }

    public static function input(): StreamableInputInterface
    {
        return self::getInstance()->input;
    }

    public static function clear(int $lines): void
    {
        for ($i = 0; $i < $lines; $i++) {
            self::output()->write("\033[1A");
            self::output()->write("\033[2K");
        }

        self::output()->write("\033[1G");
    }

    public static function reset(): void
    {
        terminal()->clear();
    }

    public static function render(string $message): ?string
    {
        return self::output()->getFormatter()->format($message);
    }

    public static function width(): int
    {
        return self::getTheme()->getConfig("width") ?? terminal()->width();
    }

    public static function color(string $colorName): OutputFormatterStyleInterface
    {
        return self::output()->getFormatter()->getStyle($colorName);
    }

    private function __construct(private readonly ConsoleOutput $output, private readonly ?StreamableInputInterface $input = null)
    {
        self::$themeLoader = new ThemeLoader(
            themes: [],
            output: $this->output
        );
    }

    public static function stream(): mixed
    {
        return self::input()->getStream() ?: STDIN;
    }

}
