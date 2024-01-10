<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command\Builder;

use Closure;
use Exception;
use Invoker\Exception\InvocationException;
use Invoker\Exception\NotCallableException;
use Invoker\Invoker;
use Invoker\InvokerInterface;
use Invoker\ParameterResolver\AssociativeArrayResolver;
use Invoker\ParameterResolver\Container\ParameterNameContainerResolver;
use Invoker\ParameterResolver\Container\TypeHintContainerResolver;
use Invoker\ParameterResolver\ResolverChain;
use Invoker\ParameterResolver\TypeHintResolver;
use Ninja\Cosmic\Command\Builder\Reflector\CallableReflector;
use Ninja\Cosmic\Command\Builder\Resolver\HyphenatedInputResolver;
use Ninja\Cosmic\Command\Builder\Validator\CallableValidator;
use Ninja\Cosmic\Command\Builder\Validator\Exception\InvalidCallableException;
use Ninja\Cosmic\Command\Command;
use Ninja\Cosmic\Command\Parser\ExpressionParser;
use Psr\Container\ContainerInterface;
use ReflectionException;
use RuntimeException;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use function Cosmic\kebabize;

final class CommandBuilder
{
    private ExpressionParser $parser;

    private InvokerInterface $invoker;

    private static ?CommandBuilder $instance = null;

    private function __construct(private readonly ?ContainerInterface $container = null)
    {
        $this->parser  = $this->createParser();
        $this->invoker = $this->createInvoker();

        if ($this->container) {
            $resolver = $this->createParameterResolver();
            $resolver->appendResolver(new TypeHintContainerResolver($this->container));
            $resolver->appendResolver(new ParameterNameContainerResolver($this->container));

            $this->invoker = new Invoker($resolver, $this->container);
        }
    }

    public static function getInstance(?ContainerInterface $container = null): CommandBuilder
    {
        if (self::$instance === null) {
            self::$instance = new self($container);
        }

        return self::$instance;
    }

    /**
     * Creates a new command from a callable.
     *
     * @param string $expression
     * @param callable|string|array<string,mixed> $callable
     * @param string[] $aliases
     *
     * @return Command|null
     * @throws ReflectionException
     * @throws InvalidCallableException
     * @throws Exception
     */
    public static function build(string $expression, callable|string|array $callable, array $aliases = []): ?Command
    {
        CallableValidator::validate($callable);

        $fn  = self::getInstance()->getCommandFunction($callable);
        $cmd = self::getInstance()->buildCommand($expression, $fn);
        $cmd->setAliases($aliases);
        $cmd->defaults(self::getInstance()->defaultsViaReflection($cmd, $callable));

        return $cmd;
    }

    /**
     * @throws Exception
     */
    private function buildCommand(string $expression, callable $callable): Command
    {
        $result = $this->parser->parse($expression);

        $command = new Command($result['name']);
        $command->getDefinition()->addArguments($result['arguments']);
        $command->getDefinition()->addOptions($result['options']);

        $command->setCode($callable);

        return $command;

    }

    /**
     * @param callable|string|array<string,mixed> $callable
     * @return callable
     */
    private function getCommandFunction(callable|string|array $callable): callable
    {
        return function (InputInterface $input, OutputInterface $output) use ($callable): mixed {
            $parameters = array_merge(
                [
                    'input'                => $input,
                    'output'               => $output,
                    InputInterface::class  => $input,
                    OutputInterface::class => $output,
                    Input::class           => $input,
                    Output::class          => $output,
                    SymfonyStyle::class    => new SymfonyStyle($input, $output),
                ],
                $input->getArguments(),
                $input->getOptions()
            );

            if ($callable instanceof Closure) {
                $callable = $callable->bindTo($this, $this);
            }

            if ($callable !== null) {
                try {
                    return $this->invoker->call($callable, $parameters);
                } catch (InvocationException $e) {
                    throw new RuntimeException(
                        sprintf(
                            "Impossible to call the '%s' command: %s",
                            $input->getFirstArgument() ?? 'UNKNOWN',
                            $e->getMessage()
                        ),
                        0,
                        $e
                    );
                }
            }

            return null;
        };
    }

    private function createInvoker(): InvokerInterface
    {
        return new Invoker($this->createParameterResolver());
    }

    private function createParser(): ExpressionParser
    {
        return new ExpressionParser();
    }

    private function createParameterResolver(): ResolverChain
    {
        return new ResolverChain([
            new AssociativeArrayResolver(),
            new HyphenatedInputResolver(),
            new TypeHintResolver(),
        ]);
    }

    /**
     * @return array<string, mixed>
     *
     * @throws NotCallableException
     * @throws ReflectionException
     */
    private function defaultsViaReflection(Command $command, mixed $callable): array
    {
        if (!is_callable($callable)) {
            return [];
        }

        $function = CallableReflector::create($callable);

        $definition = $command->getDefinition();

        $defaults = [];

        foreach ($function->getParameters() as $parameter) {
            if (!$parameter->isDefaultValueAvailable()) {
                continue;
            }

            $parameter_name       = $parameter->name;
            $hyphenated_case_name = kebabize($parameter_name);

            if ($definition->hasArgument($hyphenated_case_name) || $definition->hasOption($hyphenated_case_name)) {
                $parameter_name = $hyphenated_case_name;
            }

            if (!$definition->hasArgument($parameter_name) && !$definition->hasOption($parameter_name)) {
                continue;
            }

            $defaults[$parameter_name] = $parameter->getDefaultValue();
        }

        return $defaults;
    }

}
