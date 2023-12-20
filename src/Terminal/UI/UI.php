<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI;

use Ninja\Cosmic\Terminal\Terminal;
use Ninja\Cosmic\Terminal\UI\Element\Header;
use Ninja\Cosmic\Terminal\UI\Element\OrderedList;
use Ninja\Cosmic\Terminal\UI\Element\Paragraph;
use Ninja\Cosmic\Terminal\UI\Element\Summary;
use Ninja\Cosmic\Terminal\UI\Element\Table;
use Ninja\Cosmic\Terminal\UI\Element\UnorderedList;

class UI
{
    public const DEFAULT_OUTPUT_WIDTH            = 80;
    public const DEFAULT_HEADER_BACKGROUND_COLOR = 'default';
    public const DEFAULT_LIST_TYPE               = UnorderedList::TYPE;
    public const DEFAULT_LIST_ITEM_COLOR         = 'white';

    public static function header(
        string $message,
        string $backgroundColor = self::DEFAULT_HEADER_BACKGROUND_COLOR,
        int $width = self::DEFAULT_OUTPUT_WIDTH
    ): void {
        (new Header(Terminal::output()))($message, $backgroundColor, $width);
    }

    public static function list(
        array $items,
        string $itemColor = self::DEFAULT_LIST_ITEM_COLOR,
        string $type = self::DEFAULT_LIST_TYPE
    ): void {
        $type === UnorderedList::TYPE ?
            (new UnorderedList(Terminal::output()))($items, $itemColor) :
            (new OrderedList(Terminal::output()))($items, $itemColor);
    }

    public static function table(array $header, array $data): void
    {
        (new Table(Terminal::output()))($header, $data);
    }

    public static function summary(
        array $data,
        int $width = self::DEFAULT_OUTPUT_WIDTH,
        ?string $title = null
    ): void {
        (new Summary(Terminal::output()))($data, $width, $title);
    }

    public static function p(
        string $message,
        int $width = self::DEFAULT_OUTPUT_WIDTH
    ): void {
        (new Paragraph(Terminal::output()))($message, $width);
    }
}
