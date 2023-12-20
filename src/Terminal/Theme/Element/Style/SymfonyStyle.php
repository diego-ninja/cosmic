<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Style;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;

class SymfonyStyle extends AbstractStyle
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $fg,
        public readonly ?string $bg,
        public readonly ?array $options
    ) {}

    public static function fromArray(array $input): SymfonyStyle
    {
        return new SymfonyStyle($input["name"], $input["fg"], $input["bg"], $input["options"]);
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function load(OutputInterface $output): void
    {
        $style = new OutputFormatterStyle(
            foreground: $this->fg,
            background: $this->fg,
            options: $this->options
        );

        $output->getFormatter()->setStyle($this->name, $style);

    }
}
