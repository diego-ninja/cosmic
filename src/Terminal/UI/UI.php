<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI;

use Ninja\Cosmic\Terminal\Terminal;
use Ninja\Cosmic\Terminal\UI\Element\Header;
use Ninja\Cosmic\Terminal\UI\Element\OrderedList;
use Ninja\Cosmic\Terminal\UI\Element\Paragraph;
use Ninja\Cosmic\Terminal\UI\Element\Rule;
use Ninja\Cosmic\Terminal\UI\Element\Summary;
use Ninja\Cosmic\Terminal\UI\Element\Table;
use Ninja\Cosmic\Terminal\UI\Element\Title;
use Ninja\Cosmic\Terminal\UI\Element\UnorderedList;

/**
 * Class UI
 *
 * A utility class for building and rendering various UI elements in the terminal.
 *
 * @package Ninja\Cosmic\Terminal\UI
 */
class UI
{
    public const DEFAULT_OUTPUT_WIDTH            = 80;
    public const DEFAULT_HEADER_BACKGROUND_COLOR = 'default';
    public const DEFAULT_RULE_COLOR              = 'white';
    public const DEFAULT_LIST_TYPE               = UnorderedList::TYPE;
    public const DEFAULT_LIST_ITEM_COLOR         = 'white';

    /**
     * Display a header in the terminal.
     *
     * @param string $message           The header message.
     * @param string $backgroundColor   The background color of the header (default is 'default').
     * @param int    $width             The width of the header (default is 80).
     */
    public static function header(
        string $message,
        string $backgroundColor = self::DEFAULT_HEADER_BACKGROUND_COLOR,
        int $width = self::DEFAULT_OUTPUT_WIDTH
    ): void {
        (new Header(Terminal::output()))($message, $backgroundColor, $width);
    }

    /**
     * Display a list in the terminal.
     *
     * @param array  $items     The list items.
     * @param string $itemColor The color of the list items (default is 'white').
     * @param string $type      The type of the list (default is UnorderedList::TYPE).
     */
    public static function list(
        array $items,
        string $itemColor = self::DEFAULT_LIST_ITEM_COLOR,
        string $type = self::DEFAULT_LIST_TYPE
    ): void {
        $type === UnorderedList::TYPE ?
            (new UnorderedList(Terminal::output()))($items, $itemColor) :
            (new OrderedList(Terminal::output()))($items, $itemColor);
    }

    /**
     * Display a table in the terminal.
     *
     * @param array $header The table header.
     * @param array $data   The table data.
     */
    public static function table(array $header, array $data): void
    {
        (new Table(Terminal::output()))($header, $data);
    }

    /**
     * Display a summary in the terminal.
     *
     * @param array       $data   The summary data.
     * @param int         $width  The width of the summary (default is 80).
     * @param string|null $title  The title of the summary (optional).
     */
    public static function summary(
        array $data,
        int $width = self::DEFAULT_OUTPUT_WIDTH,
        ?string $title = null
    ): void {
        (new Summary(Terminal::output()))($data, $width, $title);
    }

    /**
     * Display a paragraph in the terminal.
     *
     * @param string $message The paragraph message.
     * @param int    $width   The width of the paragraph (default is 80).
     */
    public static function p(
        string $message,
        int $width = self::DEFAULT_OUTPUT_WIDTH
    ): void {
        (new Paragraph(Terminal::output()))($message, $width);
    }

    /**
     * Display a rule in the terminal.
     *
     * @param int    $width The width of the rule (default is 80).
     * @param string $color The color of the rule (default is 'white').
     */
    public static function rule(
        int $width = self::DEFAULT_OUTPUT_WIDTH,
        string $color = self::DEFAULT_RULE_COLOR
    ): void {
        (new Rule(Terminal::output()))($width, $color);
    }

    /**
     * Display a title in the terminal.
     *
     * @param string      $message   The title message.
     * @param string|null $subtitle  The subtitle (optional).
     * @param int         $width     The width of the title (default is 80).
     */
    public static function title(
        string $message,
        ?string $subtitle = null,
        int $width = self::DEFAULT_OUTPUT_WIDTH
    ): void {
        (new Title(Terminal::output()))($message, $subtitle, $width);
    }
}
