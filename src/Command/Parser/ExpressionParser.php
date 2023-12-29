<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command\Parser;

use Ninja\Cosmic\Command\Exception\InvalidCommandExpressionException;
use Ninja\Cosmic\Command\Input\Argument;
use Ninja\Cosmic\Command\Input\Option;

class ExpressionParser
{
    /**
     * @param string $expression
     * @return array<string, mixed>
     */
    public function parse(string $expression): array
    {
        $tokens = explode(' ', $expression);
        $tokens = array_map('trim', $tokens);
        $tokens = array_values(array_filter($tokens));

        if ($tokens === []) {
            throw new InvalidCommandExpressionException('The expression was empty');
        }

        $name = array_shift($tokens);

        $arguments = [];
        $options   = [];

        foreach ($tokens as $token) {
            if (str_starts_with($token, '--')) {
                throw new InvalidCommandExpressionException('An option must be enclosed by brackets: [--option]');
            }

            if ($this->isOption($token)) {
                $options[] = $this->parseOption($token);
            } else {
                $arguments[] = $this->parseArgument($token);
            }
        }

        return [
            'name'      => $name,
            'arguments' => $arguments,
            'options'   => $options,
        ];
    }

    private function isOption(string $token): bool
    {
        return str_starts_with($token, '[-');
    }

    private function parseArgument(string $token): Argument
    {
        if (str_ends_with($token, ']*')) {
            $mode = Argument::IS_ARRAY;
            $name = trim($token, '[]*');
        } elseif (str_ends_with($token, '*')) {
            $mode = Argument::IS_ARRAY | Argument::REQUIRED;
            $name = trim($token, '*');
        } elseif (str_starts_with($token, '[')) {
            $mode = Argument::OPTIONAL;
            $name = trim($token, '[]');
        } else {
            $mode = Argument::REQUIRED;
            $name = $token;
        }

        return new Argument($name, $mode);
    }

    private function parseOption(string $token): Option
    {
        $token = trim($token, '[]');

        // Shortcut [-y|--yell]
        if (str_contains($token, '|')) {
            [$shortcut, $token] = explode('|', $token, 2);
            $shortcut           = ltrim($shortcut, '-');
        } else {
            $shortcut = null;
        }

        $name = ltrim($token, '-');

        if (str_ends_with($token, '=]*')) {
            $mode = Option::VALUE_REQUIRED | Option::VALUE_IS_ARRAY;
            $name = substr($name, 0, -3);
        } elseif (str_ends_with($token, '=')) {
            $mode = Option::VALUE_REQUIRED;
            $name = rtrim($name, '=');
        } else {
            $mode = Option::VALUE_NONE;
        }

        return new Option($name, $shortcut, $mode);
    }
}
