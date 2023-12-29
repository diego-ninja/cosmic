<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Event;

use InvalidArgumentException;
use Ninja\Cosmic\Event\Dto\LifecycleEventArgs;

/**
 * Trait LifecycleTrait
 *
 * Provides methods to register and dispatch lifecycle events.
 */
trait LifecycleTrait
{
    /**
     * Register a listener for one or multiple lifecycle events.
     *
     * @param string|string[] $event_name The name or names of the lifecycle event(s).
     * @param callable|LifecycleEventListenerInterface $listener The listener to be registered.
     *
     * @throws InvalidArgumentException If the event name is not registered as a valid lifecycle event.
     */
    public static function registerListener(string|array $event_name, callable|LifecycleEventListenerInterface $listener): void
    {
        if (is_array($event_name)) {
            foreach ($event_name as $name) {
                self::getInstance()->register($name, $listener);
            }

            return;
        }

        self::getInstance()->register($event_name, $listener);
    }

    /**
     * Register a listener for a specific lifecycle event.
     *
     * @param string $event_name The name of the lifecycle event.
     * @param callable|LifecycleEventListenerInterface $listener The listener to be registered.
     *
     * @throws InvalidArgumentException If the event name is not registered as a valid lifecycle event.
     */
    public function register(string $event_name, callable|LifecycleEventListenerInterface $listener): void
    {
        if (!in_array($event_name, self::$lifecycle_events, true)) {
            throw new InvalidArgumentException(
                sprintf("Event name '%s' is not registered as a valid lifecycle event.", $event_name)
            );
        }

        self::$event_listeners[$event_name][] = $listener;
    }

    /**
     * Register multiple lifecycle events.
     *
     * @param string[] $lifecycle_events The array of lifecycle events to register.
     */
    public static function registerLifecycleEvents(array $lifecycle_events): void
    {
        self::$lifecycle_events = array_merge($lifecycle_events, self::$lifecycle_events);
    }

    /**
     * Dispatch a lifecycle event to its registered listeners.
     *
     * @param string $event_name The name of the lifecycle event to dispatch.
     * @param array<string, mixed> $event_args The arguments to pass to the event listeners.
     */
    public static function dispatchLifecycleEvent(string $event_name, array $event_args): void
    {
        if (isset(self::$event_listeners[$event_name])) {
            foreach (self::$event_listeners[$event_name] as $listener) {
                $listener(new LifecycleEventArgs($event_name, $event_args, self::getInstance()->getLifecycleId()));
            }
        }
    }
}
