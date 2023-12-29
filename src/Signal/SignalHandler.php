<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Signal;

use Innmind\Signals\Handler;
use Innmind\Signals\Signal;

final class SignalHandler
{
    private static ?self $instance = null;

    /**
     * @var array<array{0: Signal, 1: callable}>
     */
    private static array $listeners = [];

    public static function getInstance(): self
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self(new Handler());
        }

        return self::$instance;
    }

    private function __construct(public readonly Handler $handler) {}

    /**
     * @param array<Signal> $signals
     */
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
