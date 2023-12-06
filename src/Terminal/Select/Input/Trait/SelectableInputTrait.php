<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Select\Input\Trait;

use Ninja\Cosmic\Terminal\Select\Helper\SelectionHelper;
use Ninja\Cosmic\Terminal\Select\Input\CheckboxInput;
use Ninja\Cosmic\Terminal\Select\Input\RadioInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait SelectableInputTrait
{
    public function enableSelectHelper(InputInterface $input, OutputInterface $output): void
    {
        $this->getHelperSet()?->set(
            new SelectionHelper($input, $output)
        );
    }

    public function select(string $message, array $options, bool $allowMultiple = true)
    {
        $helper   = $this->getHelper('selection');
        $question = $allowMultiple ? new CheckboxInput($message, $options) : new RadioInput($message, $options);

        return $helper->select($question);
    }

}
