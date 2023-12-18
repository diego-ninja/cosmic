<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Input\Select\Input;

use Ninja\Cosmic\Terminal\Input\Select\Input\Exception\UnknownOptionException;

class CheckboxInput extends AbstractSelect
{
    public function select(string $option): void
    {
        if (empty(array_intersect($this->options, [$option]))) {
            throw UnknownOptionException::withOption($option);
        }

        $this->selections = array_values(array_unique(array_merge($this->selections, [$option])));
    }

    public function deselect(string $option): void
    {
        if (empty(array_intersect($this->options, [$option]))) {
            throw UnknownOptionException::withOption($option);
        }

        $this->selections = array_values(array_diff($this->selections, [$option]));
    }
}
