<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Input\Select\Input;

use BadMethodCallException;
use Ninja\Cosmic\Terminal\UI\Input\Select\Handler\SelectHandler;

final class SelectInput extends RadioInput
{
    public function __construct(string $message, array $options)
    {
        if ($options === []) {
            throw new BadMethodCallException('Can\'t create selection without options');
        }

        parent::__construct($message, $options);
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
