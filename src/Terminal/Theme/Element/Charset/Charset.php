<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Charset;

use Ninja\Cosmic\Terminal\Theme\Element\AbstractThemeElement;
use RuntimeException;

class Charset extends AbstractThemeElement
{
    public const CHAR_TOP          = "top";
    public const CHAR_TOP_MID      = "top-mid";
    public const CHAR_TOP_LEFT     = "top-left";
    public const CHAR_TOP_RIGHT    = "top-right";
    public const CHAR_BOTTOM       = "bottom";
    public const CHAR_BOTTOM_MID   = "bottom-mid";
    public const CHAR_BOTTOM_LEFT  = "bottom-left";
    public const CHAR_BOTTOM_RIGHT = "bottom-right";
    public const CHAR_LEFT         = "left";
    public const CHAR_LEFT_MID     = "left-mid";
    public const CHAR_MID          = "mid";
    public const CHAR_MID_MID      = "mid-mid";
    public const CHAR_RIGHT        = "right";
    public const CHAR_RIGHT_MID    = "right-mid";
    public const CHAR_MIDDLE       = "middle";

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

    public function __construct(public readonly string $name, public readonly array $chars) {}

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

    public function toArray(): array
    {
        return [
            "name"  => $this->name,
            "chars" => $this->chars,
        ];
    }

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
