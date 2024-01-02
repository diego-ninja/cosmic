<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Crypt;

use Stringable;

final readonly class Uid implements Stringable
{
    public function __construct(
        public string $name,
        public string $email,
        public string $trustLevel,
    ) {}

    public static function fromString(string $string): self
    {
        preg_match(
            pattern: KeyInterface::GPG_UID_REGEX_PATTERN,
            subject: $string,
            matches: $matches
        );

        return new self(
            name: $matches['name'],
            email: $matches['email'],
            trustLevel: $matches['trustLevel'],
        );
    }

    /**
     * @param array<string,string> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            trustLevel: $data['trustLevel'],
        );
    }

    public function __toString(): string
    {
        return sprintf(
            '%s <%s>',
            $this->name,
            $this->email
        );
    }
}
