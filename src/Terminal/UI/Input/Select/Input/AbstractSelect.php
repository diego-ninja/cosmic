<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Input\Select\Input;

use Ninja\Cosmic\Terminal\UI\Input\Select\Handler\SelectHandler;
use Ninja\Cosmic\Terminal\UI\Input\Select\Input\Trait\ColumnableOptionTrait;

abstract class AbstractSelect implements SelectInputInterface, ColumnAwareInterface
{
    use ColumnableOptionTrait;

    /** @var array<string> */
    protected array $selections;

    /**
     * @param string $message
     * @param array<string, string> $options
     * @param array<string> $defaultSelection
     */
    public function __construct(protected string $message, protected array $options, protected array $defaultSelection = [])
    {
        $this->selections = $defaultSelection;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return array<string, string>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return array<string>
     */
    public function getSelections(): array
    {
        return $this->selections;
    }

    public function hasSelections(): bool
    {
        return $this->selections !== [];
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
