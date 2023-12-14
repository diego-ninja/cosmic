<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Installer;

use Exception;
use Ninja\Cosmic\Environment\Env;
use Ninja\Cosmic\Terminal\Spinner\SpinnerFactory;
use RuntimeException;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

use function Cosmic\find_binary;
use function Cosmic\sudo;

class PhiveInstaller extends AbstractInstaller
{
    public const PHIVE_INSTALLATION_PATH = "/usr/local/bin/phive";
    private static array $allowed_keys   = [
        "2DF45277AEF09A2F",
        "F4D32E2C9343B2AE",
        "033E5F8D801A2F8D",
    ];

    public function __construct(OutputInterface $output)
    {
        parent::__construct($output);

        $this->preInstall(function (self $installer) {
            $installer->installPhive();
        });
    }

    public function install(): bool
    {
        if ($this->pre_install) {
            ($this->pre_install)($this);
        }

        if (!empty($this->packages)) {
            return $this->installPackages();
        }

        return true;
    }

    public function installPackages(): bool
    {
        $package_set = implode(" ", array_keys($this->packages));
        $keys        = implode(",", self::$allowed_keys);
        $command     = sudo(
            command: sprintf(
                "%s --no-progress install %s -g --trust-gpg-keys %s",
                find_binary("phive"),
                $package_set,
                $keys
            ), //phpcs:ignore
            sudo_passwd: Env::get("SUDO_PASSWORD")
        );

        $result = SpinnerFactory::for(
            callable: Process::fromShellCommandline($command),
            message: sprintf("Installing <info>%s</info> using <comment>phive</comment>", $package_set)
        );

        $this->output->writeln("\n");

        return $result;
    }

    public function isPackageInstalled(string $package): bool
    {
        $package_namespace_and_name = explode("/", $package);
        $package_name               = end($package_namespace_and_name);
        $command                    = sprintf("phive list | grep '* %s$'", $package_name);
        $process                    = Process::fromShellCommandline($command);
        $process->run();

        return $process->isSuccessful();
    }

    /**
     * @throws Exception
     */
    public function installPhive(): bool
    {
        if (!file_exists(self::PHIVE_INSTALLATION_PATH)) {
            if (
                $this->downloadPhive() && $this->addPhiveGpgKey() && $this->verifyPhive()
            ) {
                $command = sudo(
                    command: sprintf(
                        "mv /tmp/phive.phar %s && chmod a+x %s",
                        self::PHIVE_INSTALLATION_PATH,
                        self::PHIVE_INSTALLATION_PATH
                    ),
                    sudo_passwd: Env::get('SUDO_PASSWORD')
                );

                $result = SpinnerFactory::for(
                    callable: Process::fromShellCommandline($command),
                    message: "Installing <info>phive</info> into <comment>" . self::PHIVE_INSTALLATION_PATH . "</comment>"
                );

                $this->output->writeln("\n");

                return $result;
            }

            return false;
        }

        return true;
    }

    /**
     * @throws Exception
     */
    public function downloadPhive(): bool
    {
        try {
            $wget    = find_binary("wget");
            $command = sprintf("%s -O /tmp/phive.phar https://phar.io/releases/phive.phar", $wget);
            $result  = SpinnerFactory::for(
                callable: Process::fromShellCommandline($command),
                message: "Downloading <info>phive</info> binary to <comment>/tmp/phive.phar</comment>"
            );

            $this->output->writeln("");

            return $result;
        } catch (Exception $e) {
            throw new RuntimeException("Unable to download phive binary", 0, $e);
        }
    }

    /**
     * @throws Exception
     */
    public function addPhiveGpgKey(): bool
    {
        $wget    = find_binary("wget");
        $command = sprintf(
            "%s -O /tmp/phive.phar.asc https://phar.io/releases/phive.phar.asc",
            $wget
        );

        if (
            SpinnerFactory::for(
                callable: Process::fromShellCommandline($command),
                message: "Downloading <info>phive</info> GPG key"
            )
        ) {
            $this->output->writeln("");

            $command = sprintf(
                "%s --keyserver hkps://keys.openpgp.org --recv-keys 0x6AF725270AB81E04D79442549D8A98B29B2D5D79",
                find_binary("gpg")
            );

            $result = SpinnerFactory::for(
                callable: Process::fromShellCommandline($command),
                message: "Importing <info>phive</info> GPG key"
            );

            $this->output->writeln("");

            return $result;
        }

        return false;
    }

    /**
     * @throws RuntimeException
     */
    private function verifyPhive(): bool
    {
        try {
            $command = sprintf(
                "%s --verify /tmp/phive.phar.asc /tmp/phive.phar",
                find_binary("gpg")
            );

            return SpinnerFactory::for(
                callable: Process::fromShellCommandline($command),
                message: "Verifying <info>phive</info> binary"
            );
        } catch (Exception $e) {
            throw new RuntimeException("Unable to verify phive binary", 0, $e);
        }
    }

}
