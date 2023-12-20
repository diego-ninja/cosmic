<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Element;

use Ninja\Cosmic\Terminal\Terminal;

readonly class UnorderedList extends AbstractList
{
    public const TYPE = "ul";

    protected function getItems(array $items, string $itemColor): array
    {
        return array_map(
            static fn(string $item) => sprintf(
                "<div class='ml-1'>%s</div><span class='ml-1 text-%s'>%s</span>",
                Terminal::getTheme()->getIcon('bullet'),
                $itemColor,
                $item
            ),
            $items
        );

    }

}
