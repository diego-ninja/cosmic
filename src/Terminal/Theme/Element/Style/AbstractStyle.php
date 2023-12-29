<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Style;

use Ninja\Cosmic\Terminal\Theme\Element\AbstractThemeElement;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractStyle extends AbstractThemeElement
{
    final public const TERMWIND_STYLE = "termwind";
    final public const SYMFONY_STYLE  = "symfony";

    /** @phpstan-ignore-next-line  */
    abstract public static function fromArray(array $input): self;
    abstract public function load(OutputInterface $output): void;
}
