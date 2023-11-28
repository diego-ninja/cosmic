<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Event;

use Ramsey\Uuid\UuidInterface;

class Lifecycle implements LifecycleAwareInterface
{
    use LifecycleTrait;

    protected static array $event_listeners = [];

    protected static array $lifecycle_events = [];

    protected static ?self $instance = null;

    public function __construct(protected readonly ?UuidInterface $lifecycle_id = null) {}

    public function getLifecycleId(): ?UuidInterface
    {
        return $this->lifecycle_id;
    }

    public static function withLifecycleId(UuidInterface $lifecycle_id): LifecycleAwareInterface
    {
        self::$instance = new self($lifecycle_id);
        return self::$instance;
    }

    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
