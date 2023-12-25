<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Application;

use Closure;
use DI\Container;
use InvalidArgumentException;
use Invoker\Exception\InvocationException;
use Invoker\Exception\NotCallableException;
use Invoker\Invoker;
use Invoker\InvokerInterface;
use Invoker\ParameterResolver\AssociativeArrayResolver;
use Invoker\ParameterResolver\Container\ParameterNameContainerResolver;
use Invoker\ParameterResolver\Container\TypeHintContainerResolver;
use Invoker\ParameterResolver\ResolverChain;
use Invoker\ParameterResolver\TypeHintResolver;
use Ninja\Cosmic\Command\Command;
use Ninja\Cosmic\Command\CommandInterface;
use Ninja\Cosmic\Command\EnvironmentAwareInterface;
use Ninja\Cosmic\Command\Finder\CommandFinder;
use Ninja\Cosmic\Command\Parser\ExpressionParser;
use Ninja\Cosmic\Environment\Env;
use Ninja\Cosmic\Event\Lifecycle;
use Ninja\Cosmic\Reflector\CallableReflector;
use Ninja\Cosmic\Resolver\HyphenatedInputResolver;
use Ninja\Cosmic\Terminal\Terminal;
use NunoMaduro\Collision\Handler;
use NunoMaduro\Collision\Provider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use ReflectionMethod;
use RuntimeException;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

use function Cosmic\get_class_from_file;

/**
 * Class Application
 *
 * @package Ninja\Cosmic\Application
 */
final class Application extends \Symfony\Component\Console\Application
{
    public const LIFECYCLE_APP_BOOT     = 'app.boot';
    public const LIFECYCLE_APP_SHUTDOWN = 'app.shutdown';
    public const LIFECYCLE_APP_BUILD    = 'app.build';
    public const LIFECYCLE_APP_INSTALL  = 'app.install';

    private ExpressionParser $parser;

    private InvokerInterface $invoker;

    private ?ContainerInterface $container;

    /**
     * Application constructor.
     *
     * @param string $name
     * @param string $version
     * @param Container|null $container
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws InvocationException
     * @throws NotCallableException
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(string $name = 'UNKNOWN', string $version = 'UNKNOWN', ?Container $container = null)
    {
        parent::__construct($name, $version);

        $this->parser  = self::createParser();
        $this->invoker = self::createInvoker();

        $error_handler = new Provider(
            handler: (new Handler())->setOutput(Terminal::output())
        );
        $error_handler->register();

        $this->enableTheme($this->getThemeName());

        $this->withContainer($container ?? new Container(), true, true);
        $this->registerCommands([__DIR__ . "/../Command"]);

    }

    /**
     * Run the application. This is the entry point of the application.
     *
     * @param InputInterface|null $input
     * @param OutputInterface|null $output

     * @return int

     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws InvocationException
     * @throws NotCallableException
     * @throws ReflectionException
     * @throws Throwable
     */
    public function run(?InputInterface $input = null, ?OutputInterface $output = null): int
    {
        if ($output === null) {
            $output = Terminal::output();
        }

        if ($input === null) {
            $input = Terminal::input();
        }

        Lifecycle::dispatchLifecycleEvent(self::LIFECYCLE_APP_BOOT, ["app" => $this]);
        $execution_result = parent::run($input, $output);
        Lifecycle::dispatchLifecycleEvent(
            event_name: self::LIFECYCLE_APP_SHUTDOWN,
            event_args: ["app" => $this, "execution_result" => $execution_result]
        );

        return $execution_result;
    }

    /**
     * Creates an InputDefinition with the default arguments and options.
     *
     * @return InputDefinition
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws Throwable
     */
    public function getDefaultInputDefinition(): InputDefinition
    {
        $definition = parent::getDefaultInputDefinition();
        $definition->addOption(new InputOption(
            name: "theme",
            shortcut: "-t",
            mode: InputOption::VALUE_OPTIONAL,
            description: "The theme to use. Overrides the theme set in <info>.env</info> file.",
            default: null
        ));

        $definition->addOption(new InputOption(
            name: "env",
            shortcut: "-e",
            mode: InputOption::VALUE_OPTIONAL,
            description: "The environment to use. Overrides the environment set in <info>.env</info> file.",
            default: ENV_LOCAL
        ));

        $definition->addOption(new InputOption(
            name: "debug",
            shortcut: "-d",
            mode: InputOption::VALUE_NONE,
            description: "Enable debug mode."
        ));

        return $definition;
    }

    /**
     * Runs the current command.
     *
     * @param SymfonyCommand $command
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws Throwable
     */
    public function doRunCommand(SymfonyCommand $command, InputInterface $input, OutputInterface $output): int
    {
        Lifecycle::dispatchLifecycleEvent(
            event_name: CommandInterface::LIFECYCLE_COMMAND_RUN,
            event_args: ["command" => $command]
        );

        try {
            $result = parent::doRunCommand($command, $input, $output);
        } catch (Throwable $e) {
            Lifecycle::dispatchLifecycleEvent(
                event_name: CommandInterface::LIFECYCLE_COMMAND_ERROR,
                event_args: ["command" => $command, "exception" => $e]
            );
            throw $e;
        }

        if ($result === SymfonyCommand::SUCCESS) {
            Lifecycle::dispatchLifecycleEvent(
                event_name: CommandInterface::LIFECYCLE_COMMAND_SUCCESS,
                event_args: ["command" => $command]
            );
        } else {
            Lifecycle::dispatchLifecycleEvent(
                event_name: CommandInterface::LIFECYCLE_COMMAND_FAILURE,
                event_args: ["command" => $command]
            );
        }

        return $result;
    }

    /**
     * Register a command into the application.
     *
     * @param CommandInterface $command
     * @return Application
     *
     * @throws InvalidArgumentException
     * @throws InvocationException
     * @throws ReflectionException
     * @throws NotCallableException
     */
    public function registerCommand(CommandInterface $command): Application
    {

        if (is_subclass_of($command, EnvironmentAwareInterface::class)) {
            /** @var EnvironmentAwareInterface & CommandInterface $command */
            return $this->registerCommandInEnvironment($command, Env::env());
        }

        $this->command($command->getSignature(), $command::class)
            ->setName($command->getCommandName())
            ->setHidden($command->isHidden())
            ->setDecorated($command->isDecorated())
            ->descriptions($command->getCommandDescription(), $command->getArgumentDescriptions())
            ->defaults($command->getDefaults())
            ->setAliases($command->getAliases())
            ->setIcon($command->getCommandIcon())
            ->setHelp($command->getCommandHelp() ?? "")
            ->setApplication($this);

        return $this;
    }

    /**
     * Register a command into the application if the command is available in the specified environment.
     *
     * @param CommandInterface & EnvironmentAwareInterface $command
     * @param string $environment
     *
     * @return Application
     *
     * @throws NotCallableException
     * @throws InvocationException
     * @throws ReflectionException
     */
    private function registerCommandInEnvironment(
        CommandInterface & EnvironmentAwareInterface $command,
        string $environment
    ): Application {
        if ($command->isAvailableIn($environment)) {
            $this->command($command->getSignature(), $command::class)
                ->setName($command->getCommandName())
                ->setHidden($command->isHidden())
                ->setDecorated($command->isDecorated())
                ->descriptions($command->getCommandDescription(), $command->getArgumentDescriptions())
                ->defaults($command->getDefaults())
                ->setAliases($command->getAliases())
                ->setIcon($command->getCommandIcon())
                ->setHelp($command->getCommandHelp() ?? "")
                ->setApplication($this);
        }

        return $this;
    }

    /**
     * Register commands from the specified paths.
     *
     * @param array $command_paths
     * @return Application
     *
     * @throws NotCallableException
     * @throws NotFoundExceptionInterface
     * @throws InvocationException
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     */
    public function registerCommands(array $command_paths): Application
    {
        $commands = CommandFinder::find($command_paths);
        foreach ($commands as $command_file) {
            $class_name = get_class_from_file($command_file);
            $command    = $this->container->get($class_name);
            $command->setApplication($this);
            $this->registerCommand($command);
        }

        return $this;
    }

    /**
     * Set the container to use for resolving command dependencies.
     *
     * @param ContainerInterface $container
     * @param bool $byTypeHint
     * @param bool $byParameterName
     *
     * @throws InvalidArgumentException
     */
    public function withContainer(
        ContainerInterface $container,
        bool $byTypeHint = false,
        bool $byParameterName = false
    ): void {
        $this->container = $container;

        $resolver = self::createParameterResolver();
        if ($byTypeHint) {
            $resolver->appendResolver(new TypeHintContainerResolver($container));
        }
        if ($byParameterName) {
            $resolver->appendResolver(new ParameterNameContainerResolver($container));
        }

        $this->invoker = new Invoker($resolver, $container);
    }

    /**
     * Creates a new command from a callable.
     *
     * @param string $expression
     * @param callable|string $callable
     * @param array $aliases
     *
     * @return Command
     * @throws NotCallableException
     * @throws ReflectionException
     */
    public function command(string $expression, callable|string $callable, array $aliases = []): Command
    {
        $this->assertCallableIsValid($callable);

        $command_function = function (InputInterface $input, OutputInterface $output) use ($callable): mixed {
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
        };

        $command = $this->createCommand($expression, $command_function);
        $command->setAliases($aliases);

        $command->defaults($this->defaultsViaReflection($command, $callable));

        $this->add($command);

        return $command;
    }

    private function createCommand(string $expression, callable $callable): Command
    {
        $result = $this->parser->parse($expression);

        $command = new Command($result['name']);
        $command->getDefinition()->addArguments($result['arguments']);
        $command->getDefinition()->addOptions($result['options']);

        $command->setCode($callable);
        $command->setApplication($this);

        return $command;
    }

    private static function createInvoker(): InvokerInterface
    {
        return new Invoker(self::createParameterResolver());
    }

    private static function createParser(): ExpressionParser
    {
        return new ExpressionParser();
    }

    private static function createParameterResolver(): ResolverChain
    {
        return new ResolverChain([
            new AssociativeArrayResolver(),
            new HyphenatedInputResolver(),
            new TypeHintResolver(),
        ]);
    }

    /**
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
            $hyphenated_case_name = $this->fromCamelCase($parameter_name);

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

    /**
     * @throws ReflectionException
     */
    private function assertCallableIsValid(mixed $callable): void
    {
        if ($this->container) {
            return;
        }

        if ($this->isStaticCallToNonStaticMethod($callable)) {
            [$class, $method] = $callable;

            $message = "['{$class}', '{$method}'] is not a callable because '{$method}' is a static method.";
            $message .= " Either use [new {$class}(), '{$method}'] or configure a dependency injection container that supports auto wiring like PHP-DI."; //phpcs:ignore

            throw new InvalidArgumentException($message);
        }
    }

    /**
     * @throws ReflectionException
     */
    private function isStaticCallToNonStaticMethod(mixed $callable): bool
    {
        if (is_array($callable) && is_string($callable[0])) {
            [$class, $method] = $callable;
            $reflection       = new ReflectionMethod($class, $method);

            return !$reflection->isStatic();
        }

        return false;
    }

    private function fromCamelCase(string $input): string
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];

        foreach ($ret as &$match) {
            $match = $match === strtoupper($match) ? strtolower($match) : lcfirst($match);
        }

        return implode('-', $ret);
    }

    private function getThemeName(): string
    {
        if (Terminal::input()->hasParameterOption(['--theme', '-t'])) {
            return Terminal::input()->getParameterOption(['--theme', '-t']) ?? Env::get('APP_THEME', "default");
        }

        return Env::get('APP_THEME', "default");
    }

    private function enableTheme(string $theme): void
    {
        Terminal::enableTheme($theme);
        $this->setName(sprintf("%s %s", Terminal::getTheme()->getAppIcon(), Env::get("APP_NAME")));
    }

}
