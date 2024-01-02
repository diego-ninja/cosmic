<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Table;

use Ninja\Cosmic\Terminal\RenderableInterface;

/**
 * Interface TableableInterface
 * @template T
 * @extends RenderableInterface<T>
 */
interface TableableInterface extends RenderableInterface
{
    /**
     * @return array<string,mixed>
     */
    public function getTableData(): array;
    public function getTableTitle(): ?string;
    public function asTable(): Table;
}
