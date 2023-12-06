<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Select\Input;

use BadMethodCallException;
use Ninja\Cosmic\Terminal\Select\Handler\SelectHandler;

final class SelectInput extends RadioInput
{
    public function __construct(string $message, array $options)
    {
        if (!$options) {
            throw new BadMethodCallException('Can\'t create selection without options');
        }

        parent::__construct($message, $options, [$options[0]]);
    }

    public function controlMode(): int
    {
        return SelectHandler::SIMPLE_CTR;
    }

    public function getFirstSelection(): string
    {
        return $this->selections[0];
    }

}
