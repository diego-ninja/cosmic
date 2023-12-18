<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal;

use JsonException;
use Ninja\Cosmic\Terminal\Input\Select\Handler\SelectHandler;
use Ninja\Cosmic\Terminal\Input\Select\Input\CheckboxInput;
use Ninja\Cosmic\Terminal\Input\Select\Input\SelectInput;
use Ninja\Cosmic\Terminal\Table\Column\TableColumn;
use Ninja\Cosmic\Terminal\Table\Table;
use Ninja\Cosmic\Terminal\Table\TableConfig;
use Ninja\Cosmic\Terminal\Theme\ThemeInterface;
use Ninja\Cosmic\Terminal\Theme\ThemeLoader;
use Ninja\Cosmic\Terminal\Theme\ThemeLoaderInterface;
use ReflectionException;
use Symfony\Component\Console\Formatter\OutputFormatterStyleInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\StreamableInputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\OutputInterface;
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

    public static function select(
        string $message,
        array $options,
        bool $allowMultiple = true,
        ?OutputInterface $output = null,
        ?int $columns = null,
        ?int $maxWidth = null
    ): array {
        $output ??= self::output();

        $question = $allowMultiple ? new CheckboxInput($message, $options) : new SelectInput($message, $options);
        return (
            new SelectHandler(
                question: $question,
                output: $output,
                stream: self::getInstance()->getInputStream(),
                columns: $columns,
                terminalWidth: $maxWidth
            )
        )->handle();
    }
    public static function table(array $header, array $data, ?OutputInterface $output = null): void
    {
        $table = new Table(
            data: $data,
            columns: [],
            config: new TableConfig(self::getTheme()->getConfig("table"))
        );

        foreach ($header as $key => $value) {
            $table->addColumn(new TableColumn(name: $value, key: $key));
        }

        if ($output) {
            $output->writeln($table->render());
        } else {
            self::output()->writeln($table->render());
        }
    }

    public static function color(string $colorName): OutputFormatterStyleInterface
    {
        return self::output()->getFormatter()->getStyle($colorName);
    }

    private function __construct(private readonly ConsoleOutput $output, private readonly ?StreamableInputInterface $input = null)
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

    protected function getInputStream(): mixed
    {
        return self::input()->getStream() ?: STDIN;
    }

}
