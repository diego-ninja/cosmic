<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Application\Publisher\Release;

use Carbon\CarbonImmutable;
use JsonException;
use Ninja\Cosmic\Application\Publisher\Asset\Asset;
use Ninja\Cosmic\Application\Publisher\Asset\AssetCollection;
use Ninja\Cosmic\Exception\MissingInterfaceException;
use Ninja\Cosmic\Exception\UnexpectedValueException;
use Ninja\Cosmic\Serializer\SerializableInterface;
use Ninja\Cosmic\Serializer\SerializableTrait;
use Ninja\Cosmic\Terminal\Table\TableableInterface;
use Ninja\Cosmic\Terminal\Table\TableableTrait;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Release
 *
 * @package Ninja\Cosmic\Application\Publisher\Release
 */
final class Release implements TableableInterface, SerializableInterface
{
    use SerializableTrait;
    use TableableTrait;

    private AssetCollection $assets;
    public CarbonImmutable $createdAt;
    public ?CarbonImmutable $publishedAt = null;

    /**
     * Release constructor.
     *
     * @param string        $name
     * @param string        $tagName
     * @param string|null   $description
     * @param string|null   $url
     * @param bool          $isDraft
     * @param bool          $isPrerelease
     */
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
     * @param array $data
     *
     * @return self
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
     * Create a Release instance from a JSON string.
     *
     * @param string $json
     * @return self
     *
     * @throws JsonException
     */
    public static function fromJson(string $json): self
    {
        return self::fromArray(json_decode($json, true, 512, JSON_THROW_ON_ERROR));
    }

    /**
     * Get a string representation of the release.
     *
     * @return string
     */
    public function __toString(): string
    {
        return sprintf("%s [%s]", $this->name, $this->tagName);
    }

    /**
     * Get the title for the table representation.
     *
     * @return string|null
     */
    public function getTableTitle(): ?string
    {
        return sprintf("📦 Release: %s", $this);
    }

    /**
     * Get the creation date of the release.
     *
     * @return CarbonImmutable
     */
    public function getCreatedAt(): CarbonImmutable
    {
        return $this->createdAt;
    }

    /**
     * Set the creation date of the release.
     *
     * @param CarbonImmutable $createdAt
     */
    public function setCreatedAt(CarbonImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get the publication date of the release.
     *
     * @return CarbonImmutable
     */
    public function getPublishedAt(): CarbonImmutable
    {
        return $this->publishedAt;
    }

    /**
     * Set the publication date of the release.
     *
     * @param CarbonImmutable $publishedAt
     */
    public function setPublishedAt(CarbonImmutable $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    /**
     * Get the assets associated with the release.
     *
     * @return AssetCollection
     */
    public function getAssets(): AssetCollection
    {
        return $this->assets;
    }

    /**
     * Add an asset to the release.
     *
     * @param Asset $asset
     */
    public function addAsset(Asset $asset): void
    {
        $this->assets->add($asset);
    }

    /**
     * Remove an asset from the release.
     *
     * @param Asset $asset
     */
    public function removeAsset(Asset $asset): void
    {
        $this->assets->remove($asset);
    }

    /**
     * Check if the release is in draft state.
     *
     * @return bool
     */
    public function isDraft(): bool
    {
        return $this->isDraft;
    }

    /**
     * Check if the release is a pre-release.
     *
     * @return bool
     */
    public function isPrerelease(): bool
    {
        return $this->isPrerelease;
    }

    /**
     * Render the release as a table.
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
