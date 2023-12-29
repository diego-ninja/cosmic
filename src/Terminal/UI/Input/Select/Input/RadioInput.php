<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Input\Select\Input;

use Ninja\Cosmic\Terminal\UI\Input\Select\Input\Exception\UnknownOptionException;

class RadioInput extends AbstractSelect
{
    public function select(string $option): void
    {
        if (array_intersect($this->options, [$option]) === []) {
            throw UnknownOptionException::withOption($option);
        }

        $this->selections = [$option];
    }

    public function deselect(string $option): void
    {
        if (array_intersect($this->options, [$option]) === []) {
            throw UnknownOptionException::withOption($option);
        }

        $this->selections = [];
    }
}
