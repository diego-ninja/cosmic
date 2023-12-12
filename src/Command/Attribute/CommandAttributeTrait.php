<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command\Attribute;

use Ninja\Cosmic\Terminal\Terminal;
use ReflectionAttribute;

trait CommandAttributeTrait
{
    public function getCommandName(): string
    {
        $attributes = $this->reflector->getAttributes(Name::class, ReflectionAttribute::IS_INSTANCEOF);
        return $attributes[0]->newInstance()->name;
    }

    public function getSignature(): string
    {
        $attributes = $this->reflector->getAttributes(Signature::class, ReflectionAttribute::IS_INSTANCEOF);
        return $attributes[0]->newInstance()->signature;
    }

    public function getCommandDescription(): string
    {
        $attributes = $this->reflector->getAttributes(Description::class, ReflectionAttribute::IS_INSTANCEOF);
        return $attributes[0]->newInstance()->description ?? "";
    }

    public function getArgumentDescriptions(): array
    {
        $args = [];

        $attributes = array_merge(
            $this->reflector->getAttributes(Option::class, ReflectionAttribute::IS_INSTANCEOF),
            $this->reflector->getAttributes(Argument::class, ReflectionAttribute::IS_INSTANCEOF)
        );

        foreach ($attributes as $attribute) {
            $option        = $attribute->newInstance()->name;
            $args[$option] = $attribute->newInstance()->description ?? null;
        }

        return $args;
    }

    public function getDefaults(): array
    {
        $defaults = [];

        $attributes = array_merge(
            $this->reflector->getAttributes(Option::class, ReflectionAttribute::IS_INSTANCEOF),
            $this->reflector->getAttributes(Argument::class, ReflectionAttribute::IS_INSTANCEOF)
        );

        foreach ($attributes as $attribute) {
            $option            = str_replace("--", "", $attribute->newInstance()->name);
            $defaults[$option] = $attribute->newInstance()->default ?? null;
        }

        return $defaults;
    }

    public function getAliases(): array
    {
        $aliases = [];

        $attributes = $this->reflector->getAttributes(Alias::class, ReflectionAttribute::IS_INSTANCEOF);
        foreach ($attributes as $attribute) {
            $alias           = str_replace("--", "", $attribute->newInstance()->alias);
            $aliases[$alias] = $alias;
        }

        return $aliases;
    }

    public function getCommandIcon(): string
    {
        $attributes = $this->reflector->getAttributes(Icon::class, ReflectionAttribute::IS_INSTANCEOF);
        if (isset($attributes[0]) && Terminal::getTheme()->getConfig("icons_enabled")) {
            return $attributes[0]->newInstance()->icon;
        }

        return "";
    }

    public function getCommandHelp(): ?string
    {
        return null;
    }

    public function isAvailableIn(string $environment): bool
    {
        return in_array($environment, $this->getAvailableEnvironments(), true);
    }

    public function getAvailableEnvironments(): array
    {
        $envs       = [];
        $attributes = $this->reflector->getAttributes(Environment::class, ReflectionAttribute::IS_INSTANCEOF);
        foreach ($attributes as $attribute) {
            $envs[] = $attribute->newInstance()->environment;
        }

        return empty($envs) ? [ENV_LOCAL] : $envs;
    }

    public function isHidden(): bool
    {
        $attributes = $this->reflector->getAttributes(Hidden::class, ReflectionAttribute::IS_INSTANCEOF);

        if (isset($attributes[0])) {
            return $attributes[0]->newInstance()->hidden;
        }

        return false;
    }

    public function isDecorated(): bool
    {
        $attributes = $this->reflector->getAttributes(Decorated::class, ReflectionAttribute::IS_INSTANCEOF);

        if (isset($attributes[0])) {
            return $attributes[0]->newInstance()->decorated;
        }

        return true;
    }

    public function getName(): string
    {
        return $this->getCommandName();
    }
}
