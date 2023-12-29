<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element;

use Ramsey\Collection\AbstractCollection;

/**
 * Class AbstractElementCollection
 *
 * @package Ninja\Cosmic\Terminal\Theme\Element
 *
 * @template T
 * @extends AbstractCollection<AbstractThemeElement>
 */
abstract class AbstractElementCollection extends AbstractCollection
{
    abstract public function getType(): string;
    abstract public function getCollectionType(): string;

}
