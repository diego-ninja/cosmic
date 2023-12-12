<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command\Attribute;

use Attribute;
use InvalidArgumentException;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Argument
{
    public function __construct(
        public string $name,
        public string $description,
        public ?string $default = null,
    ) {
        if (str_starts_with($name, "--")) {
            throw new InvalidArgumentException("Argument name must start with a letter");
        }
    }

}
