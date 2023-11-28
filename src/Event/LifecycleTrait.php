<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Event;

use InvalidArgumentException;
use Ninja\Cosmic\Event\Dto\LifecycleEventArgs;

trait LifecycleTrait
{
    public static function registerListener(
        string | array $event_name,
        callable | LifecycleEventListenerInterface $listener
    ): void {
        if (is_array($event_name)) {
            foreach ($event_name as $name) {
                self::getInstance()->register($name, $listener);
            }

            return;
        }

        self::getInstance()->register($event_name, $listener);
    }

    private function register(string $event_name, callable | LifecycleEventListenerInterface $listener): void
    {
        if (!in_array($event_name, self::$lifecycle_events, true)) {
            throw new InvalidArgumentException(
                sprintf("Event name '%s' is not registered as valid lifecycle event.", $event_name)
            );
        }

        self::$event_listeners[$event_name][] = $listener;
    }

    public static function registerLifecycleEvents(array $lifecycle_events): void
    {
        self::$lifecycle_events = array_merge($lifecycle_events, self::$lifecycle_events);
    }

    public static function dispatchLifecycleEvent(string $event_name, array $event_args): void
    {
        if (isset(self::$event_listeners[$event_name])) {
            foreach (self::$event_listeners[$event_name] as $listener) {
                $listener(new LifecycleEventArgs($event_name, $event_args, self::getInstance()->getLifecycleId()));
            }
        }
    }
}
