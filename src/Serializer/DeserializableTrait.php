<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Serializer;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Ninja\Cosmic\Exception\UnexpectedValueException;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionProperty;

use function Cosmic\camelize;

trait DeserializableTrait
{
    /**
     * @throws ReflectionException
     * @throws UnexpectedValueException
     */
    public function fromArray(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $setter = "set" . ucfirst(camelize($key));
                if (!$this->hasMethod($setter)) {
                    continue;
                }
                $type = $this->getPropertyType($key);
                $is_nullable = $this->isPropertyNullable($key);
                if ($type !== null) {
                    $value = $is_nullable && is_null($value)
                        ? null
                        : $this->getNormalizedValue($type, $value);
                    if ((new ReflectionClass($this))->hasMethod($setter)) {
                        $this->$setter($value);
                        continue;
                    }
                    $child_prop = (new ReflectionClass($this))->getProperty($key);
                    $child_prop->getDeclaringClass()->getProperty($child_prop->getName())->setValue($this, $value);
                }
            }
        }
    }

    /**
     * @throws ReflectionException
     * @throws UnexpectedValueException
     * @throws \JsonException
     */
    public function fromJson(string $json): void
    {
        $this->fromArray(json_decode($json, true, 512, JSON_THROW_ON_ERROR));
    }

    /**
     * @throws UnexpectedValueException
     */
    protected function getPropertyType(string $property, ?string $classname = null): ?string
    {
        try {
            $type = (new ReflectionProperty($classname ?? get_class($this), $property))->getType();
            if ($type !== null) {
                /** @var ReflectionNamedType $type */
                return $type->getName();
            }

            return null;
        } catch (ReflectionException $e) {
            throw UnexpectedValueException::fromException($e);
        }
    }

    /**
     * @throws UnexpectedValueException
     */
    protected function isPropertyNullable(string $property, ?string $classname = null): bool
    {
        try {
            $type = (new ReflectionProperty($classname ?? get_class($this), $property))->getType();
            if ($type !== null) {
                /** @var ReflectionNamedType $type */
                return $type->allowsNull();
            }

            return false;
        } catch (ReflectionException $e) {
            throw UnexpectedValueException::fromException($e);
        }
    }

    protected function hasMethod(string $method): bool
    {
        return method_exists($this, $method) || method_exists($this, "__call");
    }

    protected function isDateTime(string $class): bool
    {
        if (class_exists($class)) {
            $interfaces = class_implements($class);
            return in_array(DateTimeInterface::class, $interfaces, true);
        }

        return $class === DateTimeInterface::class;
    }



    protected function getNormalizedValue(string $type, mixed $value): mixed
    {
        if ($this->isDateTime($type)) {
            return $this->getDateTimeObject($type, $value);
        }

        if (is_scalar($value) || is_array($value) || is_null($value)) {
            return $value;
        }

        throw new UnexpectedValueException();
    }


    private function getDateTimeObject(string $type, mixed $value): ?DateTimeInterface
    {
        if ($value instanceof DateTimeInterface) {
            return $value;
        }

        if ($type === DateTimeInterface::class) {
            $type = DateTimeImmutable::class;
        }

        if (is_string($value)) {
            /** @var DateTimeInterface $ret */
            $ret = new $type($value);
            return $ret;
        }

        if (isset($value["date"], $value["timezone"]) && is_array($value)) {
            /** @var DateTimeInterface */
            return new $type($value["date"], new DateTimeZone($value["timezone"]));
        }

        return null;
    }

}
