<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Element;

use Ninja\Cosmic\Terminal\Terminal;
use Ninja\Cosmic\Terminal\UI\UI;

use function Termwind\render;

abstract readonly class AbstractList extends AbstractElement
{
    public const TYPE = UI::DEFAULT_LIST_TYPE;
    public function __invoke(array $items, string $itemColor = UI::DEFAULT_LIST_ITEM_COLOR): void
    {
        render(
            sprintf(
                "<div class='ml-1 w-%d'>%s</div>",
                Terminal::width(),
                implode('', $this->getItems($items, $itemColor)),
            )
        );

        Terminal::clear(1);
    }

    abstract protected function getItems(array $items, string $itemColor): array;
}
