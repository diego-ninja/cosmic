<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Event;

use Ninja\Cosmic\Event\Dto\LifecycleEventArgs;

interface LifecycleEventListenerInterface
{
    public function __invoke(LifecycleEventArgs $args): void;
}
