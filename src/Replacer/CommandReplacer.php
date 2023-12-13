<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Replacer;

use Exception;
use Ninja\Cosmic\Command\Command;

final class CommandReplacer extends AbstractReplacer
{
    public const REPLACER_PREFIX = 'command';

    protected static ?CommandReplacer $instance = null;

    public function __construct(private readonly  Command $command)
    {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public static function forCommand(Command $command): ReplacerInterface
    {
        if (!isset(self::$instance)) {
            self::$instance = new self($command);
        }

        return self::$instance;
    }

    public function getPlaceholderValue(string $placeholder): ?string
    {
        return match ($placeholder) {
            "name"        => $this->command->getName(),
            "description" => $this->command->getDescription(),
            default       => null,
        };
    }
}
