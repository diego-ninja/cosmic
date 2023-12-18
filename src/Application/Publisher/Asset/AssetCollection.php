<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Application\Publisher\Asset;

use Ramsey\Collection\AbstractCollection;

class AssetCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Asset::class;
    }
}
