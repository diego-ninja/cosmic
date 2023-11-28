<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Option
{
    public function __construct(
        public string $option,
        public string $description,
        public ?string $default = null,
    ) {
    }
}
