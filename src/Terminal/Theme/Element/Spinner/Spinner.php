<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element\Spinner;

use Ninja\Cosmic\Terminal\Theme\Element\AbstractThemeElement;

class Spinner extends AbstractThemeElement
{
    /**
     * Spinner constructor.
     *
     * @param string $name
     * @param array<int, string> $frames
     * @param int $interval
     */
    public function __construct(public string $name, public readonly array $frames, public readonly int $interval)
    {
        parent::__construct($name);
    }

    /**
     * Create a Spinner instance from an array of data.
     *
     * @param array{
     *     name: string,
     *     frames: string[],
     *     interval: int
     * } $input
     *
     * @return Spinner
     */
    public static function fromArray(array $input): Spinner
    {
        return new Spinner(
            name: $input["name"],
            frames: $input["frames"],
            interval: $input["interval"]
        );
    }

    /**
     * Convert the Spinner instance to an array.
     *
     * @return array{
     *     name: string,
     *     frames: string[],
     *     interval: int
     * }
     */
    public function toArray(): array
    {
        return [
            "name"     => $this->name,
            "frames"   => $this->frames,
            "interval" => $this->interval,
        ];
    }
}
