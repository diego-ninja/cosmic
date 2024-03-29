<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Application\Publisher\Asset;

use Stringable;
use Carbon\CarbonImmutable;
use JsonException;
use Ninja\Cosmic\Crypt\SignerInterface;

use function Cosmic\human_filesize;

/**
 * Class Asset
 *
 * @package Ninja\Cosmic\Application
 */
class Asset implements Stringable
{
    public const STATE_UPLOADED  = 'uploaded';
    public const STATE_VERIFIED  = 'verified';
    public const STATE_PUBLISHED = 'published';
    public const STATE_UNSIGNED  = 'unsigned';

    private string $state = self::STATE_UNSIGNED;
    private CarbonImmutable $createdAt;
    private ?CarbonImmutable $updatedAt = null;
    private ?int $size                  = null;
    private ?Signature $signature       = null;
    private ?string $contentType        = null;

    public function __construct(
        public string $name,
        public ?string $path = null,
        public ?string $url = null
    ) {
        $signaturePath = sprintf('%s.%s', $path, SignerInterface::SIGNATURE_SUFFIX);
        if ($this->path !== null && file_exists($signaturePath)) {
            $signature = Signature::for($this->path);
            if ($signature->verify()) {
                $this->setSignature($signature);
                $this->setState(self::STATE_VERIFIED);
            }
        }
    }

    public function isVerified(): bool
    {
        return $this->signature instanceof Signature && $this->signature->verify();
    }

    public function setCreatedAt(CarbonImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(CarbonImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @param array<string,mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $asset = new self(
            name: $data['name'],
            path: $data['path'] ?? null,
            url: $data['url']   ?? null,
        );

        if (isset($data['createdAt'])) {
            $asset->setCreatedAt(CarbonImmutable::parse($data['createdAt']));
        }

        if (isset($data['updatedAt'])) {
            $asset->setUpdatedAt(CarbonImmutable::parse($data['updatedAt']));
        }

        if (isset($data['size'])) {
            $asset->setSize($data['size']);
        }

        if (isset($data['contentType'])) {
            $asset->setContentType($data['contentType']);
        }

        if (isset($data['state'])) {
            $asset->setState($data['state']);
        }

        return $asset;
    }

    /**
     * @param array<string,mixed> $data
     * @return self
     */
    public static function fromApiResponse(array $data): self
    {
        $asset = new self(
            name: $data['name'],
            url: $data['url']
        );

        $asset->setCreatedAt(CarbonImmutable::parse($data['created_at']));
        $asset->setUpdatedAt(CarbonImmutable::parse($data['updated_at']));
        $asset->setSize($data['size']);
        $asset->setContentType($data['content_type']);
        $asset->setState($data['state']);

        return $asset;

    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContent(): ?string
    {
        if ($this->path !== null && $content = file_get_contents($this->path)) {
            return $content;
        }

        return null;
    }

    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
    }

    public function setSize(int $size): void
    {
        $this->size = $size;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getCreatedAt(): CarbonImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?CarbonImmutable
    {
        return $this->updatedAt;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function getContentType(): ?string
    {
        if ($this->contentType !== null) {
            return $this->contentType;
        }

        if ($this->path === null) {
            return null;
        }

        $mime = mime_content_type($this->path);
        if ($mime !== false) {
            return $mime;
        }

        return null;
    }

    /**
     * @throws JsonException
     */
    public static function fromJson(string $json): self
    {
        return self::fromArray(json_decode($json, true, 512, JSON_THROW_ON_ERROR));
    }

    public function __toString(): string
    {
        if ($this->path !== null) {
            return sprintf(
                "%s [%s] [%s]",
                $this->name,
                $this->state,
                $this->size ? human_filesize($this->size) : human_filesize((int)filesize($this->path))
            );
        }

        return sprintf(
            "%s [%s]",
            $this->name,
            $this->state
        );
    }

    public function setSignature(Signature $signature): self
    {
        $this->signature = $signature;
        return $this;
    }

    public function getSignature(): ?Signature
    {
        return $this->signature;
    }
}
