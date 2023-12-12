<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Replacer;

use Exception;

abstract class AbstractReplacer implements ReplacerInterface
{
    public const REPLACER_PREFIX = null;
    public static function getInstance(): static
    {
        return new static();
    }

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

    /**
     * @throws Exception
     */
    public function __construct()
    {
        if (!defined('static::REPLACER_PREFIX')) {
            throw new Exception("Replacer prefix cannot be null");
        }
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

            if ($prefix === $this->getPrefix()) {
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

    public function getPrefix(): string
    {
        return static::REPLACER_PREFIX;
    }
    abstract public function getPlaceholderValue(string $placeholder): mixed;

}
