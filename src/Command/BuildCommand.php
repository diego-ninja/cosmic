<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command;

use Ninja\Cosmic\Application\Application;
use Ninja\Cosmic\Application\Builder\ApplicationBuilder;
use Ninja\Cosmic\Command\Attribute\Alias;
use Ninja\Cosmic\Command\Attribute\Description;
use Ninja\Cosmic\Command\Attribute\Icon;
use Ninja\Cosmic\Command\Attribute\Name;
use Ninja\Cosmic\Command\Attribute\Signature;
use Ninja\Cosmic\Environment\Env;
use Ninja\Cosmic\Event\Lifecycle;
use Ninja\Cosmic\Notifier\NotifiableInterface;
use Ninja\Cosmic\Terminal\Terminal;
use ReflectionException;

#[Icon("📦")]
#[Name("build")]
#[Description("Build <info>{env.app_name}</info> binary into <comment>builds</comment> directory")]
#[Signature("build")]
#[Alias("app:build")]
final class BuildCommand extends CosmicCommand implements NotifiableInterface
{
    public function __construct(private readonly ApplicationBuilder $builder)
    {
        parent::__construct();
    }

    /**
     * @throws ReflectionException
     */
    public function __invoke(): int
    {
        Terminal::body()->writeln("");
        if (Env::env() === ALL_OPTION) {
            $environments          = $this->extractEnvironments();
            $this->executionResult = true;

            foreach ($environments as $environment) {
                $this->executionResult = $this->builder->build($environment) && $this->executionResult;
            }
        } else {
            $this->executionResult = $this->builder->build(Env::env());
        }

        Lifecycle::dispatchLifecycleEvent(
            event_name: Application::LIFECYCLE_APP_BUILD,
            event_args: ["result" => $this->executionResult, "env" => Env::env()]
        );

        return $this->exit();
    }

    private function extractEnvironments(): array
    {
        $envs = [];
        foreach (glob("./.env.*") as $env_file) {
            if (basename($env_file) === ".env.back") {
                continue;
            }

            $envs[] = str_replace(".env.", "", basename($env_file, ".env"));
        }

        return $envs;
    }

    public function getSuccessMessage(): string
    {
        return sprintf("App %s %s was successfully built.", Env::get("APP_NAME"), Env::appVersion());
    }

    public function getErrorMessage(): string
    {
        return sprintf("Error building %s %s app", Env::get("APP_NAME"), Env::appVersion());
    }
}
