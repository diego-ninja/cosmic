<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Application\Publisher\Release;

use Carbon\CarbonImmutable;
use Ninja\Cosmic\Application\Publisher\Asset\Asset;
use Ninja\Cosmic\Application\Publisher\Asset\AssetCollection;
use Ninja\Cosmic\Terminal\RenderableInterface;
use Ninja\Cosmic\Terminal\Table\Column\TableColumn;
use Ninja\Cosmic\Terminal\Table\Table;
use Ninja\Cosmic\Terminal\Table\TableableInterface;
use Ninja\Cosmic\Terminal\Table\TableConfig;
use Symfony\Component\Console\Output\OutputInterface;

final class Release implements TableableInterface, RenderableInterface
{
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

    public function toArray(): array
    {
        return [
            'name'         => $this->name,
            'tagName'      => $this->tagName,
            'description'  => $this->description,
            'url'          => $this->url,
            'isDraft'      => $this->isDraft,
            'isPrerelease' => $this->isPrerelease,
            'createdAt'    => $this->createdAt->format('Y-m-d H:i:s'),
            'publishedAt'  => $this->publishedAt?->format('Y-m-d H:i:s') ?? "unpublished",
            'assets'       => $this->assets->toArray(),
        ];
    }

    public function getTableData(): array
    {
        $formatted   = [];
        $unformatted = $this->toArray();

        $extract = static function ($value) {
            if (is_bool($value)) {
                return $value ? 'Yes' : 'No';
            }

            if (is_array($value)) {
                return implode(', ', $value);
            }

            return $value ?? '';
        };

        foreach ($unformatted as $key => $value) {
            $key             = ucfirst($key);
            $formatted[$key] = [
                "key"   => sprintf("⬡ %s", $key),
                "value" => $extract($value),
            ];
        }

        return $formatted;
    }

    public function render(OutputInterface $output): void
    {
        $config = new TableConfig();
        $config->setShowHeader(false);
        $config->setPadding(1);

        $table = (new Table(data: $this->getTableData(), columns: [], config: $config))
            ->addColumn(new TableColumn(name: '', key: 'key', color: 'cyan'))
            ->addColumn((new TableColumn(name: '', key: 'value')));

        $table->display($output);
    }
}
