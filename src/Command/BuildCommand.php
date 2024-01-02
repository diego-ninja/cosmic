<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command;

use Exception;
use Ninja\Cosmic\Application\Application;
use Ninja\Cosmic\Application\Builder\ApplicationBuilder;
use Ninja\Cosmic\Command\Attribute\Alias;
use Ninja\Cosmic\Command\Attribute\Description;
use Ninja\Cosmic\Command\Attribute\Icon;
use Ninja\Cosmic\Command\Attribute\Name;
use Ninja\Cosmic\Command\Attribute\Option;
use Ninja\Cosmic\Command\Attribute\Signature;
use Ninja\Cosmic\Crypt\KeyRing;
use Ninja\Cosmic\Crypt\SignerInterface;
use Ninja\Cosmic\Environment\Env;
use Ninja\Cosmic\Event\Lifecycle;
use Ninja\Cosmic\Exception\BinaryNotFoundException;
use Ninja\Cosmic\Notifier\NotifiableInterface;
use Ninja\Cosmic\Terminal\Terminal;
use ReflectionException;

#[Icon("📦")]
#[Name("build")]
#[Description("Build <info>{env.app_name}</info> binary into <comment>builds</comment> directory")]
#[Signature("build [--sign]")]
#[Option("--sign", description: "Sign the binary after building it. A key for the author email must exist in the keychain.")]
#[Alias("app:build")]
final class BuildCommand extends CosmicCommand implements NotifiableInterface
{
    public function __construct(private readonly ApplicationBuilder $builder)
    {
        parent::__construct();
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function __invoke(?bool $sign): int
    {
        Terminal::output()->writeln("");
        if (Env::env() === ALL_OPTION) {
            $environments          = $this->extractEnvironments();
            $this->executionResult = true;

            foreach ($environments as $environment) {
                $this->executionResult = $this->builder->build($environment) && $this->executionResult;
            }
        } else {
            $this->executionResult = $this->builder->build(Env::env());
            if ($this->executionResult && $sign) {
                $keyring      = KeyRing::public();
                $built_binary = sprintf("./builds/%s.phar", Env::get("APP_NAME"));

                $default_key = $keyring->all()->getByEmail(Env::get("APP_AUTHOR_EMAIL"));
                if ($default_key instanceof SignerInterface) {
                    $this->executionResult = $default_key->sign($built_binary);
                }
            }
        }

        Lifecycle::dispatchLifecycleEvent(
            event_name: Application::LIFECYCLE_APP_BUILD,
            event_args: ["result" => $this->executionResult, "env" => Env::env()]
        );

        return $this->exit();
    }

    /**
     * @return array<string>
     */
    private function extractEnvironments(): array
    {
        $envs  = [];
        $files = glob("./.env.*");

        if ($files) {
            foreach ($files as $env_file) {
                if (basename($env_file) === ".env.back") {
                    continue;
                }

                $envs[] = str_replace(".env.", "", basename($env_file, ".env"));
            }
        }

        return $envs;
    }

    /**
     * @throws BinaryNotFoundException
     */
    public function getSuccessMessage(): string
    {
        return sprintf("App %s %s was successfully built.", Env::get("APP_NAME"), Env::appVersion());
    }

    /**
     * @throws BinaryNotFoundException
     */
    public function getErrorMessage(): string
    {
        return sprintf("Error building %s %s app", Env::get("APP_NAME"), Env::appVersion());
    }
}
