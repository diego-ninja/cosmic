<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal;

use Ninja\Cosmic\Terminal\Theme\ThemeInterface;
use Ninja\Cosmic\Terminal\Theme\ThemeLoader;
use Ninja\Cosmic\Terminal\Theme\ThemeLoaderInterface;
use ReflectionException;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleSectionOutput;

use function Termwind\terminal;

final class Terminal
{
    public const SECTION_HEADER = 'header';
    public const SECTION_BODY   = 'body';
    public const SECTION_FOOTER = 'footer';

    private static ?self $instance = null;

    private static ThemeLoaderInterface $themeLoader;

    private static array $sections = [];
    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self(new ConsoleOutput());
        }
        return self::$instance;
    }

    public static function withTheme(ThemeInterface $theme): void
    {
        self::getInstance()->addTheme($theme);
        self::getInstance()->enableTheme($theme->getName());
    }

    public static function addTheme(ThemeInterface $theme): void
    {
        self::$themeLoader->addTheme($theme);
    }

    public static function enableTheme(string $themeName): void
    {
        self::$themeLoader->enableTheme($themeName);
    }

    public static function getTheme(): ThemeInterface
    {
        return self::$themeLoader->getEnabledTheme();
    }

    public static function output(): ConsoleOutput
    {
        return self::getInstance()->output;
    }

    public static function header(): ConsoleSectionOutput
    {
        return self::getInstance()->getSection(self::SECTION_HEADER);
    }

    public static function body(): ConsoleSectionOutput
    {
        return self::getInstance()->getSection(self::SECTION_BODY);
    }

    public static function footer(): ConsoleSectionOutput
    {
        return self::getInstance()->getSection(self::SECTION_FOOTER);
    }

    private function clear(): void
    {
        terminal()->clear();
    }

    public static function reset(): void
    {
        self::getInstance()->clear();
    }

    public static function render(string $message): ?string
    {
        return self::output()->getFormatter()->format($message);
    }

    /**
     * @throws ReflectionException
     */
    public static function ask(
        string $message,
        ?bool $hideAnswer = false,
        ?string $default = null,
        ?iterable $autocomplete = null
    ): ?string {
        if ($autocomplete) {
            $autocomplete_options = $autocomplete;
            foreach ($autocomplete_options as $key => $value) {
                if ($value === $default) {
                    $autocomplete_options[$key] = "<span class='default-option'>{$value}</span>";
                }
            }

            $option_selector = "[" . implode(separator: '/', array: $autocomplete_options) . "]";
        } elseif ($default) {
            $option_selector = "[<span class='default-option'>{$default}</span>]";
        } else {
            $option_selector = "";
        }

        $question = sprintf(
            '<div class="mt-1 ml-2 mr-1"><span class="app-icon">%s</span><span class="question">%s</span> %s</div>',
            self::getTheme()->getAppIcon(),
            $message,
            $option_selector
        );

        return (new Question())->ask($question, $hideAnswer, $default, $autocomplete);
    }

    /**
     * @throws ReflectionException
     */
    public static function confirm(string $message, ?string $default = null): bool
    {
        if ($default !== null) {
            $answer_selector = match ($default) {
                'yes', 'y' => "[<span class='default-option'>yes</span>/no]",
                'no', 'n' => "[yes/<span class='default-option'>no</span>]",
                default => $default = null,
            };
        }

        $question = sprintf(
            '<div class="mt-1 ml-2 mr-1"><span class="app-icon">%s</span><span class="question">%s</span> %s</div>',
            self::getTheme()->getAppIcon(),
            $message,
            $answer_selector ?? "[yes/no]"
        );

        $answer = (new Question())->ask($question, false, $default, ["yes", "no"]);
        return $answer === 'yes' || $answer === 'y';
    }

    private function __construct(private readonly ConsoleOutput $output)
    {
        self::$sections[self::SECTION_HEADER] = $this->output->section();
        self::$sections[self::SECTION_BODY]   = $this->output->section();
        self::$sections[self::SECTION_FOOTER] = $this->output->section();

        self::$themeLoader = new ThemeLoader(
            themes: [],
            output: $this->output
        );
    }

    private function getSection(string $section): ConsoleSectionOutput
    {
        if (!isset(self::$sections[$section])) {
            self::$sections[$section] = $this->output->section();
        }
        return self::$sections[$section];
    }
}
