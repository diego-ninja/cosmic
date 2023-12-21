<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Application\Compiler;

use Ninja\Cosmic\Environment\Env;
use Ninja\Cosmic\Exception\BinaryNotFoundException;
use Ninja\Cosmic\Exception\ConfigFileNotFound;
use Ninja\Cosmic\Installer\PhiveInstaller;
use Ninja\Cosmic\Terminal\Input\Question;
use Ninja\Cosmic\Terminal\Spinner\SpinnerFactory;
use Ninja\Cosmic\Terminal\Terminal;
use ReflectionException;
use RuntimeException;
use Symfony\Component\Process\Process;
use Throwable;

use function Cosmic\find_binary;

/**
 * Class BoxCompiler
 *
 * Responsible for compiling the application into a Phar binary using Box.
 * @package Ninja\Cosmic\Application
 */
class BoxCompiler implements CompilerInterface
{
    /**
     * Compile the application into a Phar binary using Box.
     *
     * @return bool True if the compilation process is successful, false otherwise.
     * @throws ReflectionException
     * @throws RuntimeException|BinaryNotFoundException If unable to install or find the Box binary.
     */
    public function compile(): bool
    {
        try {
            $box_binary      = find_binary('box');
            $box_config_file = Env::basePath("box.json");

            if (!file_exists($box_config_file)) {
                throw ConfigFileNotFound::forFile($box_config_file);
            }

            $command = sprintf('%s compile --config=%s', $box_binary, $box_config_file);

            return SpinnerFactory::for(
                callable: Process::fromShellCommandline($command),
                message: sprintf(
                    'Compiling phar binary using <info>box</info> found at <comment>%s</comment>',
                    $box_binary
                ),
            );

        } catch (BinaryNotFoundException $e) {
            if ($this->installBoxBinary()) {
                return $this->compile();
            }

            throw new RuntimeException('Unable to install box binary.', 0, $e);
        } catch (Throwable $e) {
            throw new RuntimeException('Unable to find box binary.', 0, $e);
        }
    }

    /**
     * Install the Box binary using Phive.
     *
     * @return bool True if the installation process is successful, false otherwise.
     * @throws BinaryNotFoundException
     */
    private function installBoxBinary(): bool
    {
        if (Question::confirm('Do you want to install box binary?')) {
            $installer = new PhiveInstaller(Terminal::output());
            $installer->addPackage("humbug/box");
            return $installer->install();
        }

        return false;
    }
}
