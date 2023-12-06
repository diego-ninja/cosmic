<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Select\Input;

use Ninja\Cosmic\Terminal\Select\Handler\SelectHandler;
use Ninja\Cosmic\Terminal\Select\Input\Trait\ChunkableOptionTrait;

abstract class AbstractSelect implements SelectInputInterface
{
    use ChunkableOptionTrait;

    protected array $selections;

    public function __construct(protected string $message, protected array $options, protected array $defaultSelection = [])
    {
        $this->selections = $defaultSelection;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getSelections(): array
    {
        return $this->selections;
    }

    public function hasSelections(): bool
    {
        return !empty($this->selections);
    }

    public function isSelected(string $option): bool
    {
        return in_array($option, $this->selections, true);
    }

    public function controlMode(): int
    {
        return SelectHandler::DEFAULT_CTR;
    }

}
