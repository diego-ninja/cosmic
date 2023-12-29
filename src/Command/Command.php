<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command;

use Exception;
use InvalidArgumentException;
use Ninja\Cosmic\Command\Input\Argument;
use Ninja\Cosmic\Command\Input\Option;
use Ninja\Cosmic\Replacer\CommandReplacer;
use Ninja\Cosmic\Replacer\ReplacerFactory;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

/**
 * @internal
 */
final class Command extends SymfonyCommand
{
    protected string $icon = '';

    protected bool $decorated = true;

    /**
     * @throws Exception
     */
    public function __construct(string $name = null)
    {
        ReplacerFactory::registerReplacer(CommandReplacer::forCommand($this));
        parent::__construct($name);
    }

    public function isDecorated(): bool
    {
        return $this->decorated;
    }

    public function setDecorated(bool $decorated): self
    {
        $this->decorated = $decorated;
        return $this;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @param string $description
     * @param array<string,string|null> $args_descriptions
     * @return $this
     */
    public function descriptions(string $description, array $args_descriptions = []): self
    {
        $definition = $this->getDefinition();

        $this->setDescription($description);

        foreach ($args_descriptions as $name => $value) {
            if (str_starts_with($name, '--')) {
                $name = substr($name, 2);
                $this->setOptionDescription($definition, $name, $value);
            } else {
                $this->setArgumentDescription($definition, $name, $value);
            }
        }

        return $this;
    }

    /**
     * @param array<string,mixed> $defaults
     * @return $this
     */
    public function defaults(array $defaults = []): self
    {
        $definition = $this->getDefinition();

        foreach ($defaults as $name => $default) {
            if ($definition->hasArgument($name)) {
                $input = $definition->getArgument($name);
            } elseif ($definition->hasOption($name)) {
                $input = $definition->getOption($name);
            } else {
                throw new InvalidArgumentException(
                    sprintf("Unable to set default for [%s]. It does not exist as an argument or option.", $name)
                );
            }

            $input->setDefault($default);
        }

        return $this;
    }

    private function setArgumentDescription(InputDefinition $definition, string|int $name, ?string $description): void
    {
        $argument = $definition->getArgument($name);
        if ($argument instanceof Argument) {
            $argument->setDescription($description);
        }
    }

    private function setOptionDescription(InputDefinition $definition, string $name, ?string $description): void
    {
        $argument = $definition->getOption($name);
        if ($argument instanceof Option) {
            $argument->setDescription($description);
        }
    }
}
