<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Hidden
{
    public function __construct(
        public bool $hidden = true,
    ) {
    }
}
