<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element;

use Ninja\Cosmic\Serializer\SerializableInterface;
use Ninja\Cosmic\Serializer\SerializableTrait;

/**
 * Class AbstractThemeElement
 *
 * @package Ninja\Cosmic\Terminal\Theme\Element
 * @implements SerializableInterface<AbstractThemeElement>
 *
 * @property string $name
 */
abstract class AbstractThemeElement implements SerializableInterface
{
    use SerializableTrait;

    public function __construct(public string $name) {}
}
