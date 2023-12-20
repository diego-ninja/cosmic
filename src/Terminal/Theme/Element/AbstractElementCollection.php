<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element;

use Ramsey\Collection\AbstractCollection;

/** @phpstan-consistent-constructor */
abstract class AbstractElementCollection extends AbstractCollection
{
    abstract public function getType(): string;
    abstract public function getCollectionType(): string;

}
