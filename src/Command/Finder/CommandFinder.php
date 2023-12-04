<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command\Finder;

use Ninja\Cosmic\Command\CommandInterface;
use Ninja\Cosmic\Command\CosmicCommand;

use function Cosmic\get_class_from_file;

class CommandFinder
{
    public static function find(array $command_dirs, array &$commands = []): array
    {
        foreach ($command_dirs as $command_dir) {
            $contents = scandir($command_dir);
            foreach ($contents as $item) {
                if (in_array($item, ['.', '..'])) {
                    continue;
                }

                $item = sprintf("%s/%s", $command_dir, $item);
                if (is_dir($item)) {
                    $commands = array_merge($commands, self::find([$item], $commands));
                } elseif (self::isCommand($item)) {
                    $commands[] = $item;
                }
            }
        }

        return $commands;
    }

    private static function isCommand(string $filename): bool
    {
        if (preg_match("/Command\.php$/", $filename) === 1) {
            $class_name = get_class_from_file($filename);
            if (($class_name !== CosmicCommand::class) && is_subclass_of($class_name, CommandInterface::class)) {
                return true;
            }
        }

        return false;
    }
}
