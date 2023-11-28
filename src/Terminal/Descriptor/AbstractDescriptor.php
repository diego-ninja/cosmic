<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Descriptor;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Descriptor\DescriptorInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractDescriptor implements DescriptorInterface
{
    protected OutputInterface $output;

    public function describe(OutputInterface $output, object $object, array $options = []): void
    {
        $this->output = $output;

        match (true) {
            $object instanceof InputArgument   => $this->describeInputArgument($object, $options),
            $object instanceof InputOption     => $this->describeInputOption($object, $options),
            $object instanceof InputDefinition => $this->describeInputDefinition($object, $options),
            $object instanceof Command         => $this->describeCommand($object, $options),
            $object instanceof Application     => $this->describeApplication($object, $options),
            default                            => throw new InvalidArgumentException(sprintf('Object of type "%s" is not describable.', get_debug_type($object))), //phpcs:ignore
        };
    }

    protected function write(string $content, bool $decorated = false): void
    {
        $this->output->write($content, false, $decorated ? OutputInterface::OUTPUT_NORMAL : OutputInterface::OUTPUT_RAW); //phpcs:ignore
    }

    /**
     * Describes an InputArgument instance.
     */
    abstract protected function describeInputArgument(InputArgument $argument, array $options = []): void;

    /**
     * Describes an InputOption instance.
     */
    abstract protected function describeInputOption(InputOption $option, array $options = []): void;

    /**
     * Describes an InputDefinition instance.
     */
    abstract protected function describeInputDefinition(InputDefinition $definition, array $options = []): void;

    /**
     * Describes a Command instance.
     */
    abstract protected function describeCommand(Command $command, array $options = []): void;

    /**
     * Describes an Application instance.
     */
    abstract protected function describeApplication(Application $application, array $options = []): void;
}
