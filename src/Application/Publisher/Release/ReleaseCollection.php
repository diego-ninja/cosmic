<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Application\Publisher\Release;

use Ramsey\Collection\AbstractCollection;

/**
 * Class ReleaseCollection
 *
 * @package Ninja\Cosmic\Application\Publisher\Release
 */
class ReleaseCollection extends AbstractCollection
{
    /**
     * Get the type of elements in the collection.
     *
     * @return string
     */
    public function getType(): string
    {
        return Release::class;
    }
}
