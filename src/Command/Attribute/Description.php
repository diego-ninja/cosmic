<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Description
{
    public function __construct(
        public string $description,
    ) {
    }
}
