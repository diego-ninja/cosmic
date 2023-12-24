<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Event;

use Ninja\Cosmic\Event\Dto\LifecycleEventArgs;

/**
 * Interface LifecycleEventListenerInterface
 *
 * Defines the contract for an object that listens to lifecycle events.
 */
interface LifecycleEventListenerInterface
{
    /**
     * Handles a lifecycle event.
     *
     * @param LifecycleEventArgs $args The arguments associated with the lifecycle event.
     */
    public function __invoke(LifecycleEventArgs $args): void;
}
