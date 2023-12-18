<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Table;

interface TableableInterface
{
    public function getTableData(): array;
}
