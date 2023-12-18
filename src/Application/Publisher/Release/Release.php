<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Application\Publisher\Release;

use Carbon\CarbonImmutable;
use Ninja\Cosmic\Application\Publisher\Asset\Asset;
use Ninja\Cosmic\Application\Publisher\Asset\AssetCollection;
use Ninja\Cosmic\Exception\MissingInterfaceException;
use Ninja\Cosmic\Serializer\SerializableInterface;
use Ninja\Cosmic\Serializer\SerializableTrait;
use Ninja\Cosmic\Terminal\Table\TableableInterface;
use Ninja\Cosmic\Terminal\Table\TableableTrait;
use Symfony\Component\Console\Output\OutputInterface;

final class Release implements TableableInterface, SerializableInterface
{
    use SerializableTrait;
    use TableableTrait;
    private AssetCollection $assets;
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

    public static function fromJson(string $json): self
    {
        return self::fromArray(json_decode($json, true, 512, JSON_THROW_ON_ERROR));
    }

    public function __toString(): string
    {
        return sprintf("%s [%s]", $this->name, $this->tagName);
    }

    public function getCreatedAt(): CarbonImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(CarbonImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getPublishedAt(): CarbonImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(CarbonImmutable $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    public function getAssets(): AssetCollection
    {
        return $this->assets;
    }

    public function addAsset(Asset $asset): void
    {
        $this->assets->add($asset);
    }

    public function removeAsset(Asset $asset): void
    {
        $this->assets->remove($asset);
    }

    public function isDraft(): bool
    {
        return $this->isDraft;
    }

    public function isPrerelease(): bool
    {
        return $this->isPrerelease;
    }

    /**
     * @throws MissingInterfaceException
     */
    public function render(OutputInterface $output): void
    {
        $this->asTable()->display($output);
    }


}
