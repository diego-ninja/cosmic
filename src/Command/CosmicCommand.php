<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command;

use Invoker\Exception\InvocationException;
use Invoker\Exception\NotCallableException;
use Ninja\Cosmic\Application\Application;
use Ninja\Cosmic\Command\Attribute\CommandAttributeTrait;
use Ninja\Cosmic\Notifier\NotifiableInterface;
use Ninja\Cosmic\Notifier\Notifier;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class CosmicCommand implements CommandInterface, EnvironmentAwareInterface
{
    use CommandAttributeTrait;

    protected bool $executionResult;
    protected ReflectionClass $reflector;
    protected Application $application;

    public function __construct()
    {
        $this->reflector = new ReflectionClass($this);
    }

    /**
     * @throws NotCallableException
     * @throws InvocationException
     * @throws ReflectionException
     */
    public function register(Application $app): void
    {
        $app->command($this->getSignature(), static::class)
            ->setName($this->getCommandName())
            ->setHidden($this->isHidden())
            ->setDecorated($this->isDecorated())
            ->descriptions($this->getCommandDescription(), $this->getArgumentDescriptions())
            ->defaults($this->getDefaults());
    }

    public function getApplication(): Application
    {
        return $this->application;
    }

    public function setApplication(Application $app): void
    {
        $this->application = $app;
    }

    protected function success(): int
    {
        if ($this instanceof NotifiableInterface) {
            Notifier::success($this->getSuccessMessage());
        }

        return SymfonyCommand::SUCCESS;
    }

    protected function failure(): int
    {
        if ($this instanceof NotifiableInterface) {
            Notifier::error($this->getErrorMessage());
        }

        return SymfonyCommand::FAILURE;
    }

    protected function exit(): int
    {
        return $this->executionResult ? $this->success() : $this->failure();
    }
}
