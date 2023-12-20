<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Element;

use function Termwind\render;

readonly class Rule extends AbstractElement
{
    public function __invoke(int $width, string $color): void
    {
        $html = sprintf(
            "<div class='mt-1 ml-1 w-%d'><hr class='text-%s'></div>",
            $width,
            $color
        );

        render($html);
    }
}
