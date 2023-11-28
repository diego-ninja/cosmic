<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Name
{
    public function __construct(
        public string $name,
    ) {}
}
