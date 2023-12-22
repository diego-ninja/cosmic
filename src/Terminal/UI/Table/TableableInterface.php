<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Table;

use Ninja\Cosmic\Terminal\RenderableInterface;

interface TableableInterface extends RenderableInterface
{
    public function getTableData(): array;
    public function getTableTitle(): ?string;
    public function asTable(): Table;
}
