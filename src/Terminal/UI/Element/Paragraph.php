<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Element;

use function Cosmic\termwindize;
use function Termwind\render;

readonly class Paragraph extends AbstractElement
{
    public function __invoke(string $message, int $width): void
    {
        $lines = explode("\n", $message);
        $paragraph = "";

        foreach ($lines as $line) {
            $paragraph .= sprintf(
                "<div>%s</div>",
                termwindize(trim($line))
            );
        }

        render(sprintf("<div class='ml-1 mb-1 w-%d'>%s</div>", $width, $paragraph));

    }

}
