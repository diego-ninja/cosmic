<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Application\Builder;

use Exception;
use Ninja\Cosmic\Application\Compiler\BoxCompiler;
use Ninja\Cosmic\Config\Env;
use Ninja\Cosmic\Terminal\Spinner\SpinnerFactory;
use ReflectionException;
use Symfony\Component\Process\Process;

readonly class ApplicationBuilder
{
    public function __construct(private BoxCompiler $compiler) {}

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function build(string $environment): bool
    {
        return
            $this->setup($environment) && $this->compiler->compile() && $this->rename($environment) && $this->restore();
    }

    /**
     * @throws Exception
     */
    private function setup(string $env): bool
    {
        $env_file = sprintf("%s/.env.%s", Env::basePath(), $env);

        if (file_exists($env_file)) {
            $command = sprintf("cp .env .env.back; cp .env.%s .env", $env);

            return SpinnerFactory::for(
                callable: (Process::fromShellCommandline($command))->setWorkingDirectory(Env::basePath()),
                message: sprintf(
                    'Setting up the environment (<comment>%s</comment>) for building phar binary: <info>%s</info> (<cyan>%s</cyan>)',
                    $env,
                    Env::get("APP_NAME"),
                    Env::appVersion()
                )
            );
        }

        return false;
    }

    /**
     * @throws Exception
     */
    private function restore(): bool
    {
        $command = "cp .env.back .env; rm .env.back";

        return SpinnerFactory::for(
            callable: (Process::fromShellCommandline($command))->setWorkingDirectory(Env::basePath()),
            message: 'Restoring initial environment'
        );
    }

    /**
     * @throws Exception
     */
    private function rename(string $env): bool
    {
        $new_name = $env && $env !== ENV_LOCAL ?
            sprintf("%s.%s", Env::appName(), $env) :
            sprintf("%s", Env::appName());

        $command = sprintf("mv builds/%s.phar builds/%s", Env::appName(), $new_name);

        return SpinnerFactory::for(
            callable: (Process::fromShellCommandline($command))->setWorkingDirectory(Env::basePath()),
            message: sprintf(
                'Renaming built phar binary to <info>%s</info>',
                $new_name
            )
        );
    }

}
