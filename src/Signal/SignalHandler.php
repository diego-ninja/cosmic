<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Signal;

use Innmind\Signals\Handler;
use Innmind\Signals\Signal;

final class SignalHandler
{
    private static ?self $instance = null;

    private static array $listeners = [];

    public static function getInstance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self(new Handler());
        }

        return self::$instance;
    }

    private function __construct(public readonly Handler $handler) {}

    public static function listen(array $signals, callable $listener): void
    {
        foreach ($signals as $signal) {
            self::$listeners[] = [$signal, $listener];
            self::getInstance()->handler->listen($signal, $listener);
        }
    }

    public static function reset(): void
    {
        self::getInstance()->handler->reset();
    }

    public static function restore(): void
    {
        foreach (self::$listeners as [$signal, $listener]) {
            self::getInstance()->handler->listen($signal, $listener);
        }
    }
}
