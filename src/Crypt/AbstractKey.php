<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Crypt;

use Carbon\CarbonImmutable;
use Ninja\Cosmic\Exception\MissingInterfaceException;
use Ninja\Cosmic\Exception\UnexpectedValueException;
use Ninja\Cosmic\Serializer\SerializableInterface;
use Ninja\Cosmic\Serializer\SerializableTrait;
use Ninja\Cosmic\Terminal\Table\TableableInterface;
use Ninja\Cosmic\Terminal\Table\TableableTrait;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AbstractKey
 *
 * @package Ninja\Cosmic\Crypt
 *
 * @phpstan-consistent-constructor
 */
abstract class AbstractKey implements KeyInterface, SerializableInterface, TableableInterface
{
    use SerializableTrait;
    use TableableTrait;

    protected KeyCollection $subKeys;

    /**
     * AbstractKey constructor.
     *
     * @param string              $id
     * @param string              $method
     * @param string              $usage
     * @param CarbonImmutable     $createdAt
     * @param CarbonImmutable|null $expiresAt
     * @param Uid|null            $uid
     * @param string|null         $fingerprint
     */
    public function __construct(
        public readonly string $id,
        public readonly string $method,
        public readonly string $usage,
        public readonly CarbonImmutable $createdAt,
        public readonly ?CarbonImmutable $expiresAt = null,
        public readonly ?Uid $uid = null,
        public ?string $fingerprint = null
    ) {
        $this->subKeys = new KeyCollection([]);
    }

    /**
     * Check if the key is able to perform a specific usage.
     *
     * @param string $usage
     *
     * @return bool
     */
    public function isAbleTo(string $usage): bool
    {
        return str_contains($this->usage, $usage);
    }

    /**
     * Get a string representation of the key.
     *
     * @return string
     */
    public function __toString(): string
    {
        return sprintf(
            "%s %s [%s] %s",
            $this->id,
            $this->method,
            $this->usage,
            $this->uid
        );
    }

    /**
     * Create an instance of AbstractKey from an array of data.
     *
     * @param array $data
     *
     * @return static
     */
    public static function fromArray(array $data): static
    {
        return new static(
            id: $data['id'],
            method: $data['method'],
            usage: $data['usage'],
            createdAt: $data['createdAt'],
            expiresAt: $data['expiresAt']     ?? null,
            uid: $data['uid']                 ?? null,
            fingerprint: $data['fingerprint'] ?? null,
        );
    }

    /**
     * Create an instance of AbstractKey from a string.
     *
     * @param string $string
     *
     * @return static
     */
    public static function fromString(string $string): static
    {
        preg_match(
            pattern: static::GPG_PUB_REGEX_PATTERN,
            subject: $string,
            matches: $matches
        );

        return static::fromArray([
            'id'        => $matches['key_id'],
            'method'    => $matches['cypher'],
            'createdAt' => CarbonImmutable::createFromFormat('Y-m-d', $matches['created_at']),
            'expiresAt' => isset($matches["expiresAt"]) ?
                CarbonImmutable::createFromFormat('Y-m-d', $matches['expires_at']) :
                null,
            'usage' => $matches['usage'],
        ]);
    }

    /**
     * Add a sub key to the key.
     *
     * @param KeyInterface $key
     */
    public function addSubKey(KeyInterface $key): void
    {
        $this->subKeys->add($key);
    }

    /**
     * Check if the key is able to sign.
     *
     * @return bool
     */
    public function isAbleToSign(): bool
    {
        return
            is_subclass_of($this, SignerInterface::class) && $this->isAbleTo(KeyInterface::GPG_USAGE_SIGN);
    }

    /**
     * Get the signature file path for a given file path.
     *
     * @param string $file_path
     *
     * @return string
     */
    public static function getSignatureFilePath(string $file_path): string
    {
        return sprintf("%s.%s", $file_path, SignerInterface::SIGNATURE_SUFFIX);
    }

    /**
     * Render the key information to the output.
     *
     * @param OutputInterface $output
     *
     * @throws MissingInterfaceException
     * @throws UnexpectedValueException
     */
    public function render(OutputInterface $output): void
    {
        $this->asTable()->display($output);
    }
}
