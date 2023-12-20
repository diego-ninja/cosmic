<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Element;

use function Cosmic\termwindize;
use function Termwind\render;

readonly class Title extends AbstractElement
{
    public function __invoke(string $message, ?string $subtitle, int $width): void
    {
        $html = sprintf(
            '<div class="ml-1 mt-1 w-%d">
                        <div>%s</div>
                        <hr>
                        <span>%s</span>
                   </div>',
            $width,
            termwindize($message),
            $subtitle ? sprintf("<p class='italic'>%s</p>", termwindize($subtitle)) : ""
        );

        render($html);
    }

}
