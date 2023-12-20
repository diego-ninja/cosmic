<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Element;

use Symfony\Component\Console\Output\OutputInterface;
use Termwind\Termwind;

abstract readonly class AbstractElement
{
    public function __construct(protected OutputInterface $output)
    {
        Termwind::renderUsing($this->output);
    }
}
