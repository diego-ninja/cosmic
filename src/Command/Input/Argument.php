<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command\Input;

use Symfony\Component\Console\Input\InputArgument;

class Argument extends InputArgument
{
    final public const REQUIRED = 1;
    final public const OPTIONAL = 2;
    final public const IS_ARRAY = 4;

    private ?string $description;

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description ?: parent::getDescription();
    }
}
