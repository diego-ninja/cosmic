<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Replacer;

final class ReplacerFactory
{
    private static array $replacers = [];

    public static function getInstance(): self
    {
        return new self();
    }

    public static function registerReplacer(ReplacerInterface $replacer): void
    {
        self::$replacers[$replacer->getPrefix()] = $replacer;
    }

    private function getReplacers(): array
    {
        return self::$replacers;
    }

    public function replace(string $content): string
    {
        foreach ($this->getReplacers() as $replacer) {
            $content = $replacer->replace($content);
        }

        return $content;
    }

    public static function r(string $content): string
    {
        return self::getInstance()->replace($content);
    }

    private function __construct()
    {
        self::registerReplacer(new EnvironmentReplacer());
    }
}
