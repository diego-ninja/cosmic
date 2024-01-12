<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Application\Publisher\Release;

use Stringable;
use Carbon\CarbonImmutable;
use JsonException;
use Ninja\Cosmic\Application\Publisher\Asset\Asset;
use Ninja\Cosmic\Application\Publisher\Asset\AssetCollection;
use Ninja\Cosmic\Exception\MissingInterfaceException;
use Ninja\Cosmic\Exception\UnexpectedValueException;
use Ninja\Cosmic\Serializer\SerializableInterface;
use Ninja\Cosmic\Serializer\SerializableTrait;
use Ninja\Cosmic\Terminal\UI\Table\TableableInterface;
use Ninja\Cosmic\Terminal\UI\Table\TableableTrait;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Release
 *
 * @package Ninja\Cosmic\Application\Publisher\Release
 * @implements TableableInterface<Release>
 * @implements SerializableInterface<Release>
 */
final class Release implements TableableInterface, SerializableInterface, Stringable
{
    use SerializableTrait;
    use TableableTrait;

    private ?int $id = null;

    /**
     * @var AssetCollection<Asset>
     */
    private readonly AssetCollection $assets;
    public CarbonImmutable $createdAt;
    public ?CarbonImmutable $publishedAt = null;

    public function __construct(
        public readonly string $name,
        public readonly string $tagName,
        public readonly ?string $description = null,
        public readonly ?string $url = null,
        public readonly bool $isDraft = false,
        public readonly bool $isPrerelease = false,
    ) {
        $this->assets    = new AssetCollection();
        $this->createdAt = CarbonImmutable::now();
    }
    /**
     * Create a Release instance from an array of data.
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $release = new self(
            name: $data['name'],
            tagName: $data['tagName'],
            description: $data['body'] ?? null,
            url: $data['tarballUrl']   ?? null,
            isDraft: $data['isDraft'],
            isPrerelease: $data['isPrerelease'],
        );

        if (isset($data['id'])) {
            $release->setId((int)$data['id']);
        }

        if (isset($data['createdAt'])) {
            $release->setCreatedAt(CarbonImmutable::parse($data['createdAt']));
        }

        if (isset($data['publishedAt'])) {
            $release->setPublishedAt(CarbonImmutable::parse($data['publishedAt']));
        }

        foreach ($data['assets'] as $asset) {
            $release->addAsset(Asset::fromArray($asset));
        }

        return $release;
    }

    /**
     * @param array<string,mixed> $data
     * @return self
     */
    public static function fromApiResponse(array $data): self
    {
        $release = new self(
            name: $data['name'],
            tagName: $data['tag_name'],
            description: $data['body'] ?? null,
            url: $data['tarball_url'],
            isDraft: $data['draft']           ?? false,
            isPrerelease: $data['prerelease'] ?? false,
        );

        foreach ($data['assets'] as $assetData) {
            $release->addAsset(Asset::fromApiResponse($assetData));
        }

        $release->setId($data['id']);
        $release->setCreatedAt(CarbonImmutable::parse($data['created_at']));
        $release->setPublishedAt(CarbonImmutable::parse($data['published_at']));

        return $release;

    }

    /**
     * Create a Release instance from a JSON string.
     *
     *
     * @throws JsonException
     */
    public static function fromJson(string $json): self
    {
        return self::fromArray(json_decode($json, true, 512, JSON_THROW_ON_ERROR));
    }

    /**
     * Get a string representation of the release.
     */
    public function __toString(): string
    {
        return sprintf("%s [%s]", $this->name, $this->tagName);
    }

    /**
     * Get the title for the table representation.
     */
    public function getTableTitle(): ?string
    {
        return sprintf("📦 Release: %s", $this);
    }

    /**
     * Get the ID of the release.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the ID of the release.
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Get the creation date of the release.
     */
    public function getCreatedAt(): CarbonImmutable
    {
        return $this->createdAt;
    }
    /**
     * Set the creation date of the release.
     */
    public function setCreatedAt(CarbonImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
    /**
     * Get the publication date of the release.
     */
    public function getPublishedAt(): ?CarbonImmutable
    {
        return $this->publishedAt;
    }
    /**
     * Set the publication date of the release.
     */
    public function setPublishedAt(CarbonImmutable $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }
    /**
     * Get the assets associated with the release.
     * @return AssetCollection<Asset>
     */
    public function getAssets(): AssetCollection
    {
        return $this->assets;
    }
    /**
     * Add an asset to the release.
     */
    public function addAsset(Asset $asset): void
    {
        $this->assets->add($asset);
    }
    /**
     * Remove an asset from the release.
     */
    public function removeAsset(Asset $asset): void
    {
        $this->assets->remove($asset);
    }
    /**
     * Check if the release is in draft state.
     */
    public function isDraft(): bool
    {
        return $this->isDraft;
    }
    /**
     * Check if the release is a pre-release.
     */
    public function isPrerelease(): bool
    {
        return $this->isPrerelease;
    }
    /**
     * Render the release as a table.
     *
     *
     * @throws MissingInterfaceException
     * @throws UnexpectedValueException
     */
    public function render(OutputInterface $output): void
    {
        $this->asTable()->display($output);
    }
}
