<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Style;

use Symfony\Component\Console\Output\OutputInterface;

use function Termwind\style;

class TermwindStyle extends AbstractStyle
{
    public function __construct(public readonly string $name, public readonly string $style) {}

    public static function fromArray(array $input): AbstractStyle
    {
        return new TermwindStyle($input["name"], $input["style"]);
    }

    public function __toString(): string
    {
        return $this->style;
    }

    public function load(OutputInterface $output): void
    {
        style($this->name)->apply($this->style);
    }
}
