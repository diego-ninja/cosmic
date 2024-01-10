<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Input;

use Ninja\Cosmic\Terminal\Terminal;
use Ninja\Cosmic\Terminal\UI\Input\Select\Handler\SelectHandler;
use Ninja\Cosmic\Terminal\UI\Input\Select\Input\CheckboxInput;
use Ninja\Cosmic\Terminal\UI\Input\Select\Input\SelectInput;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\Question as SymfonyQuestion;

/**
 * Class Question
 *
 * A utility class for handling user input through the terminal.
 *
 * @package Ninja\Cosmic\Terminal\Input
 */
class Question
{
    /**
     * Ask a question and get the user's input.
     *
     * @param string      $message      The message to display as the question.
     * @param string|null $default      The default value for the input (optional).
     * @param string[]    $autoComplete An array of values for autocompletion (optional).
     * @param bool        $decorated    Whether to use decorated output (default is true).
     *
     * @return string|null The user's input or null if no input is provided.
     */
    public static function ask(string $message, ?string $default = null, array $autoComplete = [], bool $decorated = true): ?string
    {
        $defaultOption = $default ?? '';

        $message = $decorated ?
            sprintf(' %s <question>%s</question> [<default>%s</default>] ', Terminal::getTheme()?->getAppIcon(), $message, $defaultOption) :
            sprintf("%s [%s] ", $message, $defaultOption);

        $helper   = new QuestionHelper();
        $question = new SymfonyQuestion(Terminal::render($message) ?? "", $default);
        $question->setAutocompleterValues($autoComplete);

        /** @phpstan-ignore-next-line  */
        return $helper->ask(Terminal::input(), Terminal::output(), $question);

    }

    /**
     * Confirm a question with a yes or no answer.
     *
     * @param string $message   The message to display as the confirmation question.
     * @param bool   $default   The default answer (true for yes, false for no, default is true).
     * @param bool   $decorated Whether to use decorated output (default is true).
     *
     * @return bool The user's confirmation (true for yes, false for no).
     */
    public static function confirm(string $message, bool $default = true, bool $decorated = true): bool
    {
        $autoComplete = ['yes', 'no'];

        $message = $decorated ?
            sprintf(' %s <question>%s</question> %s ', Terminal::getTheme()?->getAppIcon(), $message, self::getAutocompleteOptions($autoComplete, $default ? 'yes' : 'no')) :
            sprintf("%s %s ", $message, self::getAutocompleteOptions($autoComplete, $default ? 'yes' : 'no'));

        $helper   = new QuestionHelper();
        $question = new SymfonyQuestion(Terminal::render($message) ?? '', $default);
        $question->setAutocompleterValues(['yes', 'no']);

        /** @phpstan-ignore-next-line  */
        $response = $helper->ask(Terminal::input(), Terminal::output(), $question);

        return in_array($response, ['yes', 'y']);
    }

    /**
     * Get hidden input from the user.
     *
     * @param string $message   The message to display as the hidden input prompt.
     * @param bool   $decorated Whether to use decorated output (default is true).
     *
     * @return string|null The hidden input or null if no input is provided.
     */
    public static function hidden(string $message, bool $decorated = true): ?string
    {
        $message = $decorated ?
            sprintf(' %s <question>%s</question> ', Terminal::getTheme()?->getAppIcon(), $message) :
            sprintf("%s ", $message);

        $helper   = new QuestionHelper();
        $question = new SymfonyQuestion(Terminal::render($message) ?? '');
        $question->setHidden(true);

        /** @phpstan-ignore-next-line  */
        return $helper->ask(Terminal::input(), Terminal::output(), $question);
    }

    /**
     * Present a selection question to the user.
     *
     * @param string $message       The message to display as the selection question.
     * @param string[]  $options    The available options for selection.
     * @param bool   $allowMultiple Whether to allow multiple selections (default is true).
     * @param int|null $columns     The number of columns to use for displaying options (optional).
     * @param int|null $maxWidth    The maximum width for displaying options (optional).
     *
     * @return string[] The selected options.
     */
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

    /**
     * Get autocomplete options with formatting for default value.
     *
     * @param string[]  $autocomplete An array of values for autocompletion.
     * @param string $default      The default value to highlight.
     *
     * @return string Formatted autocomplete options string.
     */
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
