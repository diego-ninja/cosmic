<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Serializer;

use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

use function Cosmic\snakeize;

trait GetterSetterTrait
{
    /**
     * @throws ReflectionException
     */
    public function __call(string $method, array $args): mixed
    {
        if ($this->isSetter($method)) {
            $property = $this->getProperty($method);
            if (property_exists($this, $this->getProperty($method))) {
                $this->$property = $args[0];
            }
        }

        if ($this->isGetter($method)) {
            $property = $this->getProperty($method);
            if (property_exists($this, $this->getProperty($method))) {
                return $this->isScalarOrValidObject($this, $property) ? ($this->$property ?? null) : null;
            }
        }

        return null;
    }

    protected function isSetter(string $method): bool
    {
        return str_starts_with($method, "set");
    }

    protected function isGetter(string $method): bool
    {
        return str_starts_with($method, "get");
    }

    protected function getProperty(string $method): string
    {
        return snakeize(substr($method, 3));
    }

    /**
     * @throws ReflectionException
     */
    protected function isScalarOrValidObject(mixed $object, string $property): bool
    {
        $reflection_property = new ReflectionProperty($object, $property);

        $is_object = isset($object->$property) && is_object($object->$property);
        $is_nullable = $reflection_property->getType()?->allowsNull() ?? false;

        if (!$is_object || !$is_nullable) {
            return true;
        }

        return !$this->isUninitialized($object->$property);
    }

    protected function isUninitialized(object $object): bool
    {
        $reflection = new ReflectionClass($object);
        $has_mandatory_properties = false;
        $has_initialized_mandatory_properties = false;
        foreach ($reflection->getProperties() as $reflected_property) {
            if (!($reflected_property->getType()?->allowsNull() ?? true)) {
                $has_mandatory_properties = true;
                if (
                    $reflected_property->isInitialized($object)
                    && (!is_object($reflected_property->getValue($object))
                        || !$this->isUninitialized($reflected_property->getValue($object)))
                ) {
                    $has_initialized_mandatory_properties = true;
                    break;
                }
            }
        }

        return $has_mandatory_properties && !$has_initialized_mandatory_properties;
    }

}
