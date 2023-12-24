<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Table\Column;

use Ninja\Cosmic\Terminal\UI\Table\Manipulator\ManipulatorCollection;
use Ninja\Cosmic\Terminal\UI\Table\Manipulator\TableManipulatorInterface;

interface TableColumnInterface
{
    public function getName(): string;
    public function getKey(): string;
    public function getManipulators(): ManipulatorCollection;
    public function addManipulator(TableManipulatorInterface $manipulator): self;
    public function getColor(): ?string;
}
