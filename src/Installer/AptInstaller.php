<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Installer;

use Closure;
use Exception;
use Ninja\Cosmic\Environment\Env;
use Ninja\Cosmic\Exception\BinaryNotFoundException;
use Ninja\Cosmic\Terminal\UI\Spinner\SpinnerFactory;
use Symfony\Component\Process\Process;

use function Cosmic\find_binary;
use function Cosmic\sudo;

/**
 * Class AptInstaller
 *
 * An installer implementation for Debian-based systems using apt package manager.
 */
class AptInstaller extends AbstractInstaller
{
    /**
     * Checks if a package is installed.
     *
     * @param string $package The name of the package to check.
     *
     * @return bool True if the package is installed, false otherwise.
     */
    public function isPackageInstalled(string $package): bool
    {
        $command = sprintf("dpkg-query --show --showformat='\${db:Status-Status}\\n' %s", $package);
        $process = Process::fromShellCommandline($command);
        $process->run();
        return $process->isSuccessful() && trim($process->getOutput()) === "installed";
    }

    /**
     * Installs the specified packages using apt package manager.
     *
     * @return bool True if the installation is successful, false otherwise.
     * @throws BinaryNotFoundException
     */
    public function install(): bool
    {
        if ($this->packages !== []) {
            if ($this->pre_install instanceof Closure) {
                ($this->pre_install)($this);
            }

            $package_set = implode(" ", array_keys($this->packages));

            $apt             = find_binary("apt");
            $install_command = sudo(
                sprintf("%s install --assume-yes %s", $apt, $package_set),
                Env::get('SUDO_PASSWORD', null)
            );

            $this->is_installed = SpinnerFactory::for(
                callable: Process::fromShellCommandline($install_command),
                message: sprintf("Installing <info>%s</info> using <comment>apt</comment>", $package_set)
            );

            $this->output->writeln("\n");

            if ($this->post_install instanceof Closure) {
                ($this->post_install)($this);
            }

            return $this->is_installed;
        }

        return true;
    }

    /**
     * Updates the apt package manager's sources.
     *
     * @return bool True if the update is successful, false otherwise.
     * @throws BinaryNotFoundException
     * @throws Exception
     */
    protected function updateApt(): bool
    {
        $command = sudo(sprintf("%s update", find_binary("apt")), Env::get('SUDO_PASSWORD'));
        return SpinnerFactory::for(
            callable: Process::fromShellCommandline($command),
            message: "Updating apt sources"
        );
    }
}
