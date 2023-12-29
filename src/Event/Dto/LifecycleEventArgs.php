<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Event\Dto;

use Ramsey\Uuid\UuidInterface;

use function Cosmic\snakeize;

/**
 * Class LifecycleEventArgs
 *
 * Represents the arguments associated with a lifecycle event.
 */
final class LifecycleEventArgs
{
    /**
     * @param string         $lifecycle_event The name of the lifecycle event.
     * @param array<string,mixed> $args             The arguments associated with the event.
     * @param UuidInterface|null $lifecycle_id    The UUID associated with the lifecycle event, if any.
     */
    public function __construct(
        private readonly string $lifecycle_event,
        private array $args,
        private readonly ?UuidInterface $lifecycle_id,
    ) {}

    /**
     * Gets the UUID associated with the lifecycle event.
     *
     * @return UuidInterface|null The UUID associated with the lifecycle event.
     */
    public function getLifecycleId(): ?UuidInterface
    {
        return $this->lifecycle_id;
    }

    /**
     * Gets the name of the lifecycle event.
     *
     * @return string The name of the lifecycle event.
     */
    public function getLifecycleEvent(): string
    {
        return $this->lifecycle_event;
    }

    /**
     * Gets the arguments associated with the event.
     *
     * @return array<string,mixed> The arguments associated with the event.
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * Magic method to support getter and setter methods dynamically.
     *
     * @param string $method The method name.
     * @param array<int|string,mixed>  $args   The method arguments.
     *
     * @return mixed|null The value if it exists, otherwise null.
     */
    public function __call(string $method, array $args): mixed
    {
        if ($this->isSetter($method)) {
            $property              = $this->getProperty($method);
            $this->args[$property] = $args[0];
        }

        if ($this->isGetter($method)) {
            $property = $this->getProperty($method);
            if (array_key_exists($property, $this->args)) {
                return $this->args[$property];
            }
        }

        return null;
    }

    /**
     * Checks if the method is a setter.
     *
     * @param string $method The method name.
     *
     * @return bool Whether the method is a setter.
     */
    private function isSetter(string $method): bool
    {
        return str_starts_with($method, "set");
    }

    /**
     * Checks if the method is a getter.
     *
     * @param string $method The method name.
     *
     * @return bool Whether the method is a getter.
     */
    private function isGetter(string $method): bool
    {
        return str_starts_with($method, "get");
    }

    /**
     * Converts a method name to a property name.
     *
     * @param string $method The method name.
     *
     * @return string The property name.
     */
    protected function getProperty(string $method): string
    {
        return snakeize(substr($method, 3));
    }
}
