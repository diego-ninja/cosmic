<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Event;

use Ramsey\Uuid\UuidInterface;

interface LifecycleAwareInterface
{
    public static function registerListener(
        string|array $event_name,
        callable|LifecycleEventListenerInterface $listener
    ): void;
    public static function dispatchLifecycleEvent(string $event_name, array $event_args): void;
    public static function registerLifecycleEvents(array $lifecycle_events): void;
    public function getLifecycleId(): ?UuidInterface;
}
