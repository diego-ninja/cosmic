<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Installer;

use Ninja\Cosmic\Environment\Env;
use Ninja\Cosmic\Terminal\Spinner\SpinnerFactory;
use Symfony\Component\Process\Process;

use function Cosmic\find_binary;
use function Cosmic\sudo;

class AptInstaller extends AbstractInstaller
{
    public function isPackageInstalled(string $package): bool
    {
        $command = sprintf("dpkg-query --show --showformat='\${db:Status-Status}\\n' %s", $package);
        $process = Process::fromShellCommandline($command);
        $process->run();
        return $process->isSuccessful() && trim($process->getOutput()) === "installed";
    }

    public function install(): bool
    {
        if (!empty($this->packages)) {
            if ($this->pre_install) {
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

            if ($this->post_install) {
                ($this->post_install)($this);
            }

            return $this->is_installed;
        }

        return true;
    }

    protected function updateApt(): bool
    {
        $command = sudo(sprintf("%s update", find_binary("apt")), Env::get('SUDO_PASSWORD'));
        return SpinnerFactory::for(
            callable: Process::fromShellCommandline($command),
            message: "Updating apt sources"
        );
    }
}
