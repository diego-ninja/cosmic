<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal;

use Symfony\Component\Console\Output\OutputInterface;

interface RenderableInterface
{
    public function render(OutputInterface $output): void;
}
