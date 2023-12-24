<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Event;

use Ramsey\Uuid\UuidInterface;

/**
 * Class Lifecycle
 *
 * Represents the lifecycle of an event in the application.
 */
class Lifecycle implements LifecycleAwareInterface
{
    use LifecycleTrait;

    /** @var array<string, array<callable>> The list of event listeners. */
    protected static array $event_listeners = [];

    /** @var array<string, array> The list of lifecycle events. */
    protected static array $lifecycle_events = [];

    /** @var self|null The singleton instance of the Lifecycle class. */
    protected static ?self $instance = null;

    /**
     * Lifecycle constructor.
     *
     * @param UuidInterface|null $lifecycle_id The UUID representing the lifecycle of an event.
     */
    public function __construct(protected readonly ?UuidInterface $lifecycle_id = null) {}

    /**
     * Get the UUID representing the lifecycle of an event.
     *
     * @return UuidInterface|null The UUID or null if not set.
     */
    public function getLifecycleId(): ?UuidInterface
    {
        return $this->lifecycle_id;
    }

    /**
     * Create a new instance of Lifecycle with the given lifecycle ID.
     *
     * @param UuidInterface $lifecycle_id The UUID representing the lifecycle of an event.
     *
     * @return LifecycleAwareInterface The new instance of Lifecycle.
     */
    public static function withLifecycleId(UuidInterface $lifecycle_id): LifecycleAwareInterface
    {
        self::$instance = new self($lifecycle_id);
        return self::$instance;
    }

    /**
     * Get the singleton instance of the Lifecycle class.
     *
     * @return LifecycleAwareInterface The instance of Lifecycle.
     */
    public static function getInstance(): LifecycleAwareInterface
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
