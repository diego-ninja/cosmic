<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Element;

use function Cosmic\termwindize;
use function Termwind\render;

readonly class Header extends AbstractElement
{
    public function __invoke(string $message, string $backgroundColor, int $width): void
    {
        render(
            sprintf(
                "<div class='m-1 bg-%s p-4 w-%d'>%s</div>",
                $backgroundColor,
                $width,
                termwindize($message)
            )
        );

    }

}
