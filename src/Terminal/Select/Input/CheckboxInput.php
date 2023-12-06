<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Select\Input;

use Ninja\Cosmic\Terminal\Select\Input\Exception\UnknownOptionException;

class CheckboxInput extends AbstractSelect
{
    public function select(string $option): void
    {
        if (empty(array_intersect($this->options, [$option]))) {
            throw UnknownOptionException::withOption($option);
        }

        $this->selections = $this->isSelected($option) ?
            array_values(array_diff($this->selections, [$option])) :
            [$option];
    }
}
