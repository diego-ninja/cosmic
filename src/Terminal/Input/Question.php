<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Input;

use Ninja\Cosmic\Terminal\Input\Select\Handler\SelectHandler;
use Ninja\Cosmic\Terminal\Input\Select\Input\CheckboxInput;
use Ninja\Cosmic\Terminal\Input\Select\Input\SelectInput;
use Ninja\Cosmic\Terminal\Terminal;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\Question as SymfonyQuestion;

class Question
{
    public static function ask(string $message, ?string $default = null, array $autoComplete = [], bool $decorated = true): ?string
    {
        $defaultOption = $default ?? '';

        $message = $decorated ?
            sprintf(' %s <question>%s</question> [<default>%s</default>] ', Terminal::getTheme()->getAppIcon(), $message, $defaultOption) :
            sprintf("%s [%s] ", $message, $defaultOption);

        $helper   = new QuestionHelper();
        $question = new SymfonyQuestion(Terminal::render($message), $default);
        $question->setAutocompleterValues($autoComplete);

        return $helper->ask(Terminal::input(), Terminal::output(), $question);

    }

    public static function confirm(string $message, bool $default = true, bool $decorated = true): bool
    {
        $autoComplete = ['yes', 'no'];

        $message = $decorated ?
            sprintf(' %s <question>%s</question> %s ', Terminal::getTheme()->getAppIcon(), $message, self::getAutocompleteOptions($autoComplete, $default ? 'yes' : 'no')) :
            sprintf("%s %s ", $message, self::getAutocompleteOptions($autoComplete, $default ? 'yes' : 'no'));

        $helper   = new QuestionHelper();
        $question = new SymfonyQuestion(Terminal::render($message), $default);
        $question->setAutocompleterValues(['yes', 'no']);

        $response = $helper->ask(Terminal::input(), Terminal::output(), $question);
        return in_array($response, ['yes', 'y']);
    }

    public static function hidden(string $message, bool $decorated = true): ?string
    {
        $message = $decorated ?
            sprintf(' %s <question>%s</question> ', Terminal::getTheme()->getAppIcon(), $message) :
            sprintf("%s ", $message);

        $helper   = new QuestionHelper();
        $question = new SymfonyQuestion(Terminal::render($message));
        $question->setHidden(true);

        return $helper->ask(Terminal::input(), Terminal::output(), $question);
    }

    public static function select(
        string $message,
        array $options,
        bool $allowMultiple = true,
        ?int $columns = null,
        ?int $maxWidth = null
    ): array {

        $question = $allowMultiple ? new CheckboxInput($message, $options) : new SelectInput($message, $options);
        return (
        new SelectHandler(
            question: $question,
            output: Terminal::output(),
            stream: Terminal::stream(),
            columns: $columns,
            terminalWidth: $maxWidth ?? Terminal::width()
        )
        )->handle();
    }

    private static function getAutocompleteOptions(array $autocomplete, string $default): string
    {
        $autocomplete_options = $autocomplete;
        foreach ($autocomplete_options as $key => $value) {
            if ($value === $default) {
                $autocomplete_options[$key] = Terminal::render(sprintf("<default>%s</default>", $value));
            }
        }

        return "[" . implode(separator: '/', array: $autocomplete_options) . "]";
    }
}
