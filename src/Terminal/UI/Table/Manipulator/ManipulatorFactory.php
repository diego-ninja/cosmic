<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Table\Manipulator;

use RuntimeException;

class ManipulatorFactory
{
    /**
     * @throws RuntimeException
     */
    public static function create(string $type): TableManipulatorInterface
    {
        $manipulatorClass = 'Ninja\\Cosmic\\Terminal\\Table\\Manipulator\\' . ucfirst($type) . 'Manipulator';

        if (!class_exists($manipulatorClass)) {
            throw new RuntimeException('Manipulator class ' . $manipulatorClass . ' does not exist.');
        }

        /** @var TableManipulatorInterface $manipulator */
        $manipulator = new $manipulatorClass();
        return $manipulator;
    }
}
