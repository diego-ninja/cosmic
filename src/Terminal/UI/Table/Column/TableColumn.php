<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Table\Column;

use Ninja\Cosmic\Terminal\UI\Table\Manipulator\ManipulatorCollection;
use Ninja\Cosmic\Terminal\UI\Table\Manipulator\TableManipulatorInterface;
use Ninja\Cosmic\Terminal\UI\Table\TableConfig;

class TableColumn implements TableColumnInterface
{
    private readonly ManipulatorCollection $manipulators;

    public function __construct(
        public readonly string $name,
        public readonly string $key,
        public readonly ?string $color = TableConfig::DEFAULT_FIELD_COLOR
    ) {
        $this->manipulators = new ManipulatorCollection();
    }

    public static function create(
        string $name,
        string $key,
        ?string $color
    ): self {
        return new self($name, $key, $color);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function getManipulators(): ManipulatorCollection
    {
        return $this->manipulators;
    }

    public function addManipulator(TableManipulatorInterface $manipulator): self
    {
        $this->manipulators->add($manipulator);
        return $this;
    }
}
