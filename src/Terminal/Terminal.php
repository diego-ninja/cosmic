<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal;

use JsonException;
use Ninja\Cosmic\Exception\BinaryNotFoundException;
use Ninja\Cosmic\Terminal\Theme\Exception\ThemeNotFoundException;
use Ninja\Cosmic\Terminal\Theme\ThemeInterface;
use Ninja\Cosmic\Terminal\Theme\ThemeLoader;
use Ninja\Cosmic\Terminal\Theme\ThemeLoaderInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyleInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\StreamableInputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

use function Termwind\terminal;

/**
 * Class Terminal
 *
 * A utility class for interacting with the terminal and managing themes.
 *
 * @package Ninja\Cosmic\Terminal
 */
final class Terminal
{
    private static ?self $instance = null;

    private static ThemeLoaderInterface $themeLoader;

    /**
     * Get the singleton instance of the Terminal class.
     *
     * @return self The Terminal instance.
     */
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

    /**
     * Configure and enable a theme for the terminal.
     *
     * @param ThemeInterface $theme The theme to enable.
     *
     * @return self The Terminal instance.
     */
    public static function withTheme(ThemeInterface $theme): self
    {
        self::getInstance()->addTheme($theme);
        self::getInstance()->enableTheme($theme->getName());
        return self::getInstance();
    }

    /**
     * Load themes from a specified directory.
     *
     * @param string $directory The directory containing theme configurations.
     *
     * @return self The Terminal instance.
     * @throws JsonException
     * @throws BinaryNotFoundException
     *
     */
    public static function loadThemes(string $directory): self
    {
        $instance = self::getInstance();
        self::$themeLoader->loadDirectory($directory);

        return $instance;
    }

    /**
     * Add a theme to the list of available themes.
     *
     * @param ThemeInterface $theme The theme to add.
     *
     * @return self The Terminal instance.
     */
    public static function addTheme(ThemeInterface $theme): self
    {
        self::$themeLoader->addTheme($theme);
        return self::getInstance();
    }

    /**
     * Enable a specific theme by name.
     *
     * @param string $themeName The name of the theme to enable.
     *
     * @return self The Terminal instance.
     * @throws ThemeNotFoundException
     */
    public static function enableTheme(string $themeName): self
    {
        try {
            self::$themeLoader->enableTheme($themeName);
        } catch (ThemeNotFoundException) {
            self::$themeLoader->enableTheme(ThemeInterface::DEFAULT_THEME);
        } finally {
            return self::getInstance();
        }
    }

    /**
     * Get the currently enabled theme.
     *
     * @return ThemeInterface|null The currently enabled theme.
     */
    public static function getTheme(?string $themeName = null): ?ThemeInterface
    {
        if ($themeName !== null) {
            return self::$themeLoader->getTheme($themeName);
        }

        return self::$themeLoader->getEnabledTheme();
    }

    /**
     * Get the console output object.
     *
     * @return ConsoleOutput The console output object.
     */
    public static function output(): ConsoleOutput
    {
        return self::getInstance()->output;
    }

    /**
     * Get the streamable input object.
     *
     * @return StreamableInputInterface|null The streamable input object.
     */
    public static function input(): ?StreamableInputInterface
    {
        return self::getInstance()->input;
    }

    /**
     * Clear a specified number of lines from the terminal.
     *
     * @param int $lines The number of lines to clear.
     */
    public static function clear(int $lines): void
    {
        for ($i = 0; $i < $lines; $i++) {
            self::output()->write("\033[1A");
            self::output()->write("\033[2K");
        }

        self::output()->write("\033[1G");
    }

    /**
     * Reset the terminal using Termwind.
     */
    public static function reset(): void
    {
        terminal()->clear();
    }

    /**
     * Render a message using the terminal's formatter.
     *
     * @param string $message The message to render.
     *
     * @return string|null The rendered message.
     */
    public static function render(string $message): ?string
    {
        return self::output()->getFormatter()->format($message);
    }

    /**
     * Get the width of the terminal.
     *
     * @return int The width of the terminal.
     */
    public static function width(): int
    {
        return self::getTheme()?->getConfig("width") ?? terminal()->width();
    }

    /**
     * Get a color style by name from the terminal formatter.
     *
     * @param string $colorName The name of the color style.
     *
     * @return OutputFormatterStyleInterface The color style.
     */
    public static function color(string $colorName): OutputFormatterStyleInterface
    {
        return self::output()->getFormatter()->getStyle($colorName);
    }

    /**
     * Hide the cursor.
     * @param resource $stream
     */
    public static function hideCursor(mixed $stream = STDOUT): void
    {
        fprintf($stream, "\033[?25l"); // hide cursor
        register_shutdown_function(static function () use ($stream): void {
            self::restoreCursor($stream = STDOUT);
        });
    }

    /**
     * Restore the cursor to its original position.
     * @param resource $stream
     */
    public static function restoreCursor(mixed $stream = STDOUT): void
    {
        self::output()->write("\033[?25h");
    }

    /**
     * Get the stream associated with the terminal input.
     *
     * @return resource The input stream or STDIN if not available.
     */
    public static function stream(): mixed
    {
        return self::input()?->getStream() ?: STDIN;
    }

    private function __construct(
        private readonly ConsoleOutput $output,
        private readonly ?StreamableInputInterface $input = null
    ) {
        self::$themeLoader = new ThemeLoader(
            themes: [],
            output: $this->output
        );
    }
}
