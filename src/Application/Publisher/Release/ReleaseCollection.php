<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Application\Publisher\Release;

use Ramsey\Collection\AbstractCollection;

class ReleaseCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Release::class;
    }
}
