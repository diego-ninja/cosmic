<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Crypt;

use Carbon\CarbonImmutable;
use Ninja\Cosmic\Terminal\RenderableInterface;
use Ninja\Cosmic\Terminal\Table\Column\TableColumn;
use Ninja\Cosmic\Terminal\Table\Table;
use Ninja\Cosmic\Terminal\Table\TableableInterface;
use Ninja\Cosmic\Terminal\Table\TableConfig;
use Symfony\Component\Console\Output\OutputInterface;

/** @phpstan-consistent-constructor */
abstract class AbstractKey implements KeyInterface, TableableInterface, RenderableInterface
{
    protected KeyCollection $keys;
    public function __construct(
        public readonly string $id,
        public readonly string $method,
        public readonly string $usage,
        public readonly CarbonImmutable $createdAt,
        public readonly ?CarbonImmutable $expiresAt = null,
        public readonly ?Uid $uid = null,
        public ?string $fingerprint = null
    ) {
        $this->keys = new KeyCollection([]);
    }

    public function isAbleTo(string $usage): bool
    {
        return str_contains($this->usage, $usage);
    }

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

    public function addSubKey(KeyInterface $key): void
    {
        $this->keys->add($key);
    }

    public function isAbleToSign(): bool
    {
        return
            is_subclass_of($this, SignerInterface::class) && $this->isAbleTo(KeyInterface::GPG_USAGE_SIGN);
    }

    public static function getSignatureFilePath(string $file_path): string
    {
        return sprintf("%s.%s", $file_path, SignerInterface::SIGNATURE_SUFFIX);
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

    public function getTableData(): array
    {
        return [
            'Key ID' => [
                "key"   => "⬡ Key ID",
                "value" => $this->id ?? null,
            ],
            'Method' => [
                "key"   => "⬡ Method",
                "value" => $this->method ?? null,
            ],
            'Created' => [
                "key"   => "⬡ Created",
                "value" => $this->createdAt->toFormattedDateString(),
            ],
            'Expires' => [
                "key"   => "⬡ Expires",
                "value" => $this->expiresAt ? $this->expiresAt->toFormattedDateString() : '',
            ],
            'Usage' => [
                "key"   => "⬡ Usage",
                "value" => $this->usage ?? null,
            ],
            'Fingerprint' => [
                "key"   => "⬡ Fingerprint",
                "value" => $this->fingerprint ?? null,
            ],
            'User ID' => [
                "key"   => "⬡ User ID",
                "value" => (string)$this->uid,
            ],
        ];
    }
}
