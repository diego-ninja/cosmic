<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Event;

use Ramsey\Uuid\UuidInterface;

/**
 * Interface LifecycleAwareInterface
 *
 * Defines the contract for an object that is aware of its lifecycle events.
 */
interface LifecycleAwareInterface
{
    /**
     * Register a listener for one or multiple lifecycle events.
     *
     * @param string|string[] $event_name The name or names of the lifecycle event(s).
     * @param callable|LifecycleEventListenerInterface $listener The listener to be registered.
     */
    public static function registerListener(string|array $event_name, callable|LifecycleEventListenerInterface $listener): void;

    /**
     * Dispatch a lifecycle event to its registered listeners.
     *
     * @param string $event_name The name of the lifecycle event to dispatch.
     * @param array<int|string, mixed> $event_args The arguments to pass to the event listeners.
     */
    public static function dispatchLifecycleEvent(string $event_name, array $event_args): void;

    /**
     * Register multiple lifecycle events.
     *
     * @param string[] $lifecycle_events The array of lifecycle events to register.
     */
    public static function registerLifecycleEvents(array $lifecycle_events): void;

    /**
     * @param string $event_name
     * @param callable|LifecycleEventListenerInterface $listener
     * @return void
     */
    public function register(string $event_name, callable|LifecycleEventListenerInterface $listener): void;

    /**
     * Get the unique identifier associated with the object's lifecycle.
     *
     * @return UuidInterface|null The UUID representing the lifecycle, or null if not set.
     */
    public function getLifecycleId(): ?UuidInterface;
}
