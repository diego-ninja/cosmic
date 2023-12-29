<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Charset;

use Ninja\Cosmic\Terminal\Theme\Element\AbstractThemeElement;
use RuntimeException;

class Charset extends AbstractThemeElement
{
    final public const CHAR_TOP          = "top";
    final public const CHAR_TOP_MID      = "top-mid";
    final public const CHAR_TOP_LEFT     = "top-left";
    final public const CHAR_TOP_RIGHT    = "top-right";
    final public const CHAR_BOTTOM       = "bottom";
    final public const CHAR_BOTTOM_MID   = "bottom-mid";
    final public const CHAR_BOTTOM_LEFT  = "bottom-left";
    final public const CHAR_BOTTOM_RIGHT = "bottom-right";
    final public const CHAR_LEFT         = "left";
    final public const CHAR_LEFT_MID     = "left-mid";
    final public const CHAR_MID          = "mid";
    final public const CHAR_MID_MID      = "mid-mid";
    final public const CHAR_RIGHT        = "right";
    final public const CHAR_RIGHT_MID    = "right-mid";
    final public const CHAR_MIDDLE       = "middle";

    private const CHARS = [
        self::CHAR_TOP,
        self::CHAR_TOP_MID,
        self::CHAR_TOP_LEFT,
        self::CHAR_TOP_RIGHT,
        self::CHAR_BOTTOM,
        self::CHAR_BOTTOM_MID,
        self::CHAR_BOTTOM_LEFT,
        self::CHAR_BOTTOM_RIGHT,
        self::CHAR_LEFT,
        self::CHAR_LEFT_MID,
        self::CHAR_MID,
        self::CHAR_MID_MID,
        self::CHAR_RIGHT,
        self::CHAR_RIGHT_MID,
        self::CHAR_MIDDLE,
    ];

    /**
     * Charset constructor.
     *
     * @param string $name
     * @param array<string, string> $chars
     */
    public function __construct(public string $name, public readonly array $chars)
    {
        parent::__construct($name);
    }

    /**
     * Create a Charset instance from an array of data.
     *
     * @param array{
     *     name: string,
     *     chars: array<string, string>
     * } $input
     *
     * @return Charset
     */
    public static function fromArray(array $input): Charset
    {
        if (!self::isComplete($input["chars"])) {
            throw new RuntimeException("Charset is not complete");
        }

        return new Charset($input["name"], $input["chars"]);

    }

    public function char(string $name): string
    {
        return $this->chars[$name];
    }

    /**
     * @return array<string, string|array<string,string>>
     */
    public function toArray(): array
    {
        return [
            "name"  => $this->name,
            "chars" => $this->chars,
        ];
    }

    /**
     * @param array<string,string> $chars
     */
    private static function isComplete(array $chars): bool
    {
        foreach (self::CHARS as $char) {
            if (!isset($chars[$char])) {
                return false;
            }
        }

        return true;
    }

}
