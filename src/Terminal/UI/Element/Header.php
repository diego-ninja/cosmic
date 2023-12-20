<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Element;

use Ninja\Cosmic\Terminal\Terminal;
use Termwind\Termwind;

use function Termwind\render;

readonly class Header extends AbstractElement
{
    public function __invoke(string $message, string $backgroundColor, int $width): void
    {
        Termwind::renderUsing($this->output);
        render(
            sprintf(
                "<div class='m-1 bg-%s p-4 w-%d'>%s</div>",
                $backgroundColor,
                $width,
                Terminal::render($message)
            )
        );

    }

}
