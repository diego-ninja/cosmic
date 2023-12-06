<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Select\Helper;

use Ninja\Cosmic\Terminal\Select\Handler\SelectHandler;
use Ninja\Cosmic\Terminal\Select\Helper\Trait\StreamableInputTrait;
use Ninja\Cosmic\Terminal\Select\Input\SelectInput;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SelectionHelper implements HelperInterface
{
    use StreamableInputTrait;

    protected ?HelperSet $helperSet = null;

    public function __construct(protected InputInterface $input, protected OutputInterface $output)
    {
        $this->checkAnsiSupport();
        $this->setOutputStyles();
    }

    /**
     * {@inheritdoc}
     */
    public function setHelperSet(HelperSet $helperSet = null): void
    {
        $this->helperSet = $helperSet;
    }

    /**
     * {@inheritdoc}
     */
    public function getHelperSet(): ?HelperSet
    {
        return $this->helperSet;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'selection';
    }

    /**
     * Allow multiple item selections to user.
     *
     * @return list<string>
     */
    public function select(SelectInput $question): array
    {
        return (new SelectHandler($question, $this->output, $this->getInputStream()))->handle();
    }

    protected function checkAnsiSupport(): void
    {
        if ($this->output->isDecorated()) {
            return;
        }

        // // disable overwrite when output does not support ANSI codes.
        // $this->overwrite = false;
        // // set a reasonable redraw frequency so output isn't flooded
        // $this->setRedrawFrequency(10);
    }

    protected function setOutputStyles(): void
    {
        if (!$this->output->getFormatter()->hasStyle('hl')) {
            $style = new OutputFormatterStyle('black', 'white');
            $this->output->getFormatter()->setStyle('hl', $style);
        }
    }

}
