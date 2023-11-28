<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Decorated
{
    public function __construct(
        public bool $decorated = true,
    ) {}
}
