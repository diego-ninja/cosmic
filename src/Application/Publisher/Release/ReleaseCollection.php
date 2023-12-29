<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Application\Publisher\Release;

use Ramsey\Collection\AbstractCollection;

/**
 * Class ReleaseCollection
 *
 * @package Ninja\Cosmic\Application\Publisher\Release
 * @template T
 * @extends AbstractCollection<Release>
 */
class ReleaseCollection extends AbstractCollection
{
    /**
     * Get the type of elements in the collection.
     */
    public function getType(): string
    {
        return Release::class;
    }
}
