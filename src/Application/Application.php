<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Application;

use DI\Container;
use Exception;
use InvalidArgumentException;
use Ninja\Cosmic\Command\BuildCommand;
use Ninja\Cosmic\Command\Builder\CommandBuilder;
use Ninja\Cosmic\Command\Command;
use Ninja\Cosmic\Command\CommandInterface;
use Ninja\Cosmic\Command\EnvironmentAwareInterface;
use Ninja\Cosmic\Command\Finder\CommandFinder;
use Ninja\Cosmic\Command\InitCommand;
use Ninja\Cosmic\Command\InstallCommand;
use Ninja\Cosmic\Command\PublishCommand;
use Ninja\Cosmic\Command\SignCommand;
use Ninja\Cosmic\Environment\Env;
use Ninja\Cosmic\Event\Lifecycle;
use Ninja\Cosmic\Terminal\Terminal;
use NunoMaduro\Collision\Handler;
use NunoMaduro\Collision\Provider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use RuntimeException;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
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
    public const LIFECYCLE_APP_SIGNALED = 'app.signal';

    private const COSMIC_COMMANDS = [
        BuildCommand::class,
        InstallCommand::class,
        InitCommand::class,
        PublishCommand::class,
        SignCommand::class,
    ];

    private ?ContainerInterface $container;

    /**
     * Application constructor.
     *
     * @param string $name
     * @param string $version
     * @param Container|null $container
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function __construct(string $name = 'UNKNOWN', string $version = 'UNKNOWN', ?Container $container = null)
    {
        parent::__construct($name, $version);

        $error_handler = new Provider(
            handler: (new Handler())->setOutput(Terminal::output())
        );
        $error_handler->register();

        $this
            ->withContainer($container ?? new Container())
            ->enableTheme($this->getThemeName())
            ->registerCommands([__DIR__ . "/../Command"]);
    }

    /**
     * Run the application. This is the entry point of the application.
     *
     * @param InputInterface|null $input
     * @param OutputInterface|null $output
     *
     *
     * @return int
     * @throws Exception
     */
    public function run(?InputInterface $input = null, ?OutputInterface $output = null): int
    {
        if (!$output instanceof OutputInterface) {
            $output = Terminal::output();
        }

        if (!$input instanceof InputInterface) {
            $input = Terminal::input();
        }

        Lifecycle::dispatchLifecycleEvent(self::LIFECYCLE_APP_BOOT, ["app" => $this]);
        $execution_result = parent::run($input, $output);
        Lifecycle::dispatchLifecycleEvent(
            event_name: self::LIFECYCLE_APP_SHUTDOWN,
            event_args: ["app" => $this, "execution_result" => $execution_result]
        );

        Terminal::restoreCursor();

        return $execution_result;
    }

    /**
     * Creates an InputDefinition with the default arguments and options.
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
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    public function registerCommand(CommandInterface $command): Application
    {

        if (is_subclass_of($command, EnvironmentAwareInterface::class)) {
            /** @var EnvironmentAwareInterface & CommandInterface $command */
            return $this->registerCommandInEnvironment($command, Env::env());
        }

        if (!$this->isCommandDisabled($command)) {
            $this->command($command->getSignature(), $command::class)
                ?->setName($command->getCommandName())
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
     * Register a command into the application if the command is available in the specified environment.
     * @throws ReflectionException
     */
    private function registerCommandInEnvironment(
        CommandInterface & EnvironmentAwareInterface $command,
        string $environment
    ): Application {
        if ($command->isAvailableIn($environment) && !$this->isCommandDisabled($command)) {
            $this->command($command->getSignature(), $command::class)
                ?->setName($command->getCommandName())
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
     * @param string[] $command_paths
     * @return Application
     *
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     */
    public function registerCommands(array $command_paths): Application
    {
        $commands = CommandFinder::find($command_paths);
        foreach ($commands as $command_file) {
            $class_name = get_class_from_file($command_file);
            $command    = $this->container?->get($class_name);
            if ($command instanceof CommandInterface) {
                $command->setApplication($this);
                $this->registerCommand($command);
            }
        }

        return $this;
    }

    private function isCommandDisabled(CommandInterface $command): bool
    {
        return
            in_array($command::class, self::COSMIC_COMMANDS, true) && Env::get("DISABLE_COSMIC_COMMANDS", false);
    }

    /**
     * Set the container to use for resolving command dependencies.
     *
     * @throws InvalidArgumentException
     */
    public function withContainer(
        ContainerInterface $container
    ): self {
        $this->container = $container;
        CommandBuilder::getInstance($container);

        return $this;
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
     * @throws Exception
     */
    public function command(string $expression, callable|string|array $callable, array $aliases = []): ?Command
    {
        $command = CommandBuilder::build($expression, $callable, $aliases);
        if ($command) {
            $command->setApplication($this);
            $this->add($command);

            return $command;
        }

        return null;
    }

    private function getThemeName(): string
    {
        if (Terminal::input()?->hasParameterOption(['--theme', '-t'])) {
            return Terminal::input()->getParameterOption(['--theme', '-t']) ?? Env::get('APP_THEME', "cosmic");
        }

        return Env::get('APP_THEME', "cosmic");
    }

    private function enableTheme(string $theme): self
    {
        Terminal::enableTheme($theme);
        $this->setName(sprintf("%s %s", Terminal::getTheme()?->getAppIcon(), Env::get("APP_NAME")));

        return $this;
    }
}
