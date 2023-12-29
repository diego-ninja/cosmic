<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Element;

readonly class OrderedList extends AbstractList
{
    final public const TYPE = "ol";

    protected function getItems(array $items, string $itemColor): array
    {
        return array_map(
            static fn(string $item, int $index): string => sprintf("<div class='ml-1'>%d.</div><span class='ml-1 text-%s'>%s</span>", $index + 1, $itemColor, $item),
            $items,
            array_keys($items),
        );

    }
}
