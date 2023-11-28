<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Event\Dto;

use Ramsey\Uuid\UuidInterface;

final class LifecycleEventArgs
{
    public function __construct(
        private readonly string $lifecycle_event,
        private array $args,
        private readonly ?UuidInterface $lifecycle_id,
    ) {}

    public function getLifecycleId(): ?UuidInterface
    {
        return $this->lifecycle_id;
    }

    public function getLifecycleEvent(): string
    {
        return $this->lifecycle_event;
    }

    public function getArgs(): array
    {
        return $this->args;
    }

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

    private function isSetter(string $method): bool
    {
        return str_starts_with($method, "set");
    }

    private function isGetter(string $method): bool
    {
        return str_starts_with($method, "get");
    }

    protected function getProperty(string $method): string
    {
        return snakeize(substr($method, 3));
    }
}
