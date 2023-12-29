<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command\Input;

use Symfony\Component\Console\Input\InputOption;

class Option extends InputOption
{
    final public const VALUE_NONE      = 1;
    final public const VALUE_REQUIRED  = 2;
    final public const VALUE_OPTIONAL  = 4;
    final public const VALUE_IS_ARRAY  = 8;
    final public const VALUE_NEGATABLE = 16;

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
