<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command\Input;

use Symfony\Component\Console\Input\InputArgument;

class Argument extends InputArgument
{
    public const REQUIRED = 1;
    public const OPTIONAL = 2;
    public const IS_ARRAY = 4;

    private string $description;

    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description ?: parent::getDescription();
    }
}
