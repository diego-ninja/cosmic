<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Alias
{
    public function __construct(
        public string $alias,
    ) {}
}
