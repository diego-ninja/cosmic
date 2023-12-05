<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Replacer;

interface ReplacerInterface
{
    public const PLACEHOLDER_PATTERN = '/\{(.*?)\}/';
    public static function replace(string $content): string;
}
