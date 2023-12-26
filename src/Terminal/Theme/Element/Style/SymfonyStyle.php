<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Style;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;

class SymfonyStyle extends AbstractStyle
{
    public function __construct(
        public readonly string  $name,
        public readonly ?string $foreground,
        public readonly ?string $background,
        public readonly ?array  $options
    ) {}

    public static function fromArray(array $input): SymfonyStyle
    {
        return new SymfonyStyle(
            name: $input["name"],
            foreground: $input["fg"],
            background: $input["bg"],
            options: $input["options"]
        );
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function load(OutputInterface $output): void
    {
        $style = new OutputFormatterStyle(
            foreground: $this->foreground,
            background: $this->background,
            options: $this->options
        );

        $output->getFormatter()->setStyle($this->name, $style);

    }
}
