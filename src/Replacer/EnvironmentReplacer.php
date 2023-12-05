<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Replacer;

use Ninja\Cosmic\Config\Env;

class EnvironmentReplacer implements ReplacerInterface
{
    public const REPLACER_PREFIX = 'env';
    public static function replace(string $content): string
    {
        $placeholders = self::getInstance()->extractPlaceholders(
            content: $content
        );

        foreach ($placeholders as $key => $placeholder) {
            $value = self::getInstance()->getPlaceholderValue(
                placeholder: $key
            );

            $content = self::getInstance()->replacePlaceholder(
                content: $content,
                placeholder: "{" . $placeholder . "}",
                value: $value
            );
        }

        return $content;
    }

    public static function getInstance(): self
    {
        return new self();
    }

    public function extractPlaceholders(string $content): array
    {
        $placeholders = [];
        preg_match_all(
            pattern: self::PLACEHOLDER_PATTERN,
            subject: $content,
            matches: $matches
        );

        foreach ($matches[1] as $placeholder) {
            [$prefix, $key] = explode(
                separator: '.',
                string: $placeholder
            );

            if ($prefix === self::REPLACER_PREFIX) {
                $placeholders[$key] = $placeholder;
            }
        }

        return $placeholders;
    }

    public function replacePlaceholder(string $content, string $placeholder, mixed $value): string
    {
        return str_replace(
            search: $placeholder,
            replace: $value,
            subject: $content
        );
    }

    public function getPlaceholderValue(string $placeholder): mixed
    {
        if ($placeholder === "shell") {
            return Env::shell();
        }
        return Env::get(strtoupper($placeholder));
    }
}
