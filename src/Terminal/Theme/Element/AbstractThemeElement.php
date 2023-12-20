<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element;

use Ninja\Cosmic\Serializer\SerializableInterface;
use Ninja\Cosmic\Serializer\SerializableTrait;

abstract class AbstractThemeElement implements SerializableInterface
{
    use SerializableTrait;
}
