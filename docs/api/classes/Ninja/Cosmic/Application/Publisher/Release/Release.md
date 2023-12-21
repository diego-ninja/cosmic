***

# Release

Class Release



* Full name: `\Ninja\Cosmic\Application\Publisher\Release\Release`
* This class is marked as **final** and can't be subclassed
* This class implements:
[`\Ninja\Cosmic\Terminal\Table\TableableInterface`](../../../Terminal/Table/TableableInterface.md), [`\Ninja\Cosmic\Serializer\SerializableInterface`](../../../Serializer/SerializableInterface.md)
* This class is a **Final class**



## Properties


### assets



```php
private \Ninja\Cosmic\Application\Publisher\Asset\AssetCollection $assets
```






***

### createdAt



```php
public \Carbon\CarbonImmutable $createdAt
```






***

### publishedAt



```php
public ?\Carbon\CarbonImmutable $publishedAt
```






***

### name



```php
public string $name
```






***

### tagName



```php
public string $tagName
```






***

### description



```php
public ?string $description
```






***

### url



```php
public ?string $url
```






***

### isDraft



```php
public bool $isDraft
```






***

### isPrerelease



```php
public bool $isPrerelease
```






***

## Methods


### __construct

Release constructor.

```php
public __construct(string $name, string $tagName, string|null $description = null, string|null $url = null, bool $isDraft = false, bool $isPrerelease = false): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |
| `$tagName` | **string** |  |
| `$description` | **string&#124;null** |  |
| `$url` | **string&#124;null** |  |
| `$isDraft` | **bool** |  |
| `$isPrerelease` | **bool** |  |





***

### fromArray

Create a Release instance from an array of data.

```php
public static fromArray(array $data): self
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **array** |  |





***

### fromJson

Create a Release instance from a JSON string.

```php
public static fromJson(string $json): self
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$json` | **string** |  |




**Throws:**

- [`JsonException`](../../../../../JsonException.md)



***

### __toString

Get a string representation of the release.

```php
public __toString(): string
```












***

### getTableTitle

Get the title for the table representation.

```php
public getTableTitle(): string|null
```












***

### getCreatedAt

Get the creation date of the release.

```php
public getCreatedAt(): \Carbon\CarbonImmutable
```












***

### setCreatedAt

Set the creation date of the release.

```php
public setCreatedAt(\Carbon\CarbonImmutable $createdAt): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$createdAt` | **\Carbon\CarbonImmutable** |  |





***

### getPublishedAt

Get the publication date of the release.

```php
public getPublishedAt(): \Carbon\CarbonImmutable
```












***

### setPublishedAt

Set the publication date of the release.

```php
public setPublishedAt(\Carbon\CarbonImmutable $publishedAt): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$publishedAt` | **\Carbon\CarbonImmutable** |  |





***

### getAssets

Get the assets associated with the release.

```php
public getAssets(): \Ninja\Cosmic\Application\Publisher\Asset\AssetCollection
```












***

### addAsset

Add an asset to the release.

```php
public addAsset(\Ninja\Cosmic\Application\Publisher\Asset\Asset $asset): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$asset` | **\Ninja\Cosmic\Application\Publisher\Asset\Asset** |  |





***

### removeAsset

Remove an asset from the release.

```php
public removeAsset(\Ninja\Cosmic\Application\Publisher\Asset\Asset $asset): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$asset` | **\Ninja\Cosmic\Application\Publisher\Asset\Asset** |  |





***

### isDraft

Check if the release is in draft state.

```php
public isDraft(): bool
```












***

### isPrerelease

Check if the release is a pre-release.

```php
public isPrerelease(): bool
```












***

### render

Render the release as a table.

```php
public render(\Symfony\Component\Console\Output\OutputInterface $output): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$output` | **\Symfony\Component\Console\Output\OutputInterface** |  |




**Throws:**

- [`MissingInterfaceException`](../../../Exception/MissingInterfaceException.md)

- [`UnexpectedValueException`](../../../Exception/UnexpectedValueException.md)



***


## Inherited methods


### toJson



```php
public toJson(): string
```











**Throws:**

- [`JsonException`](../../../../../JsonException.md)



***

### toArray



```php
public toArray(): ?array
```












***

### jsonSerialize



```php
public jsonSerialize(): ?array
```












***

### getTableData



```php
public getTableData(): array
```











**Throws:**

- [`MissingInterfaceException`](../../../Exception/MissingInterfaceException.md)

- [`UnexpectedValueException`](../../../Exception/UnexpectedValueException.md)



***

### asTable



```php
public asTable(): \Ninja\Cosmic\Terminal\Table\Table
```











**Throws:**

- [`MissingInterfaceException`](../../../Exception/MissingInterfaceException.md)

- [`UnexpectedValueException`](../../../Exception/UnexpectedValueException.md)



***

### getTableTitle



```php
public getTableTitle(): ?string
```












***

### extractValue



```php
private extractValue(mixed $value): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$value` | **mixed** |  |





***


***
> Automatically generated on 2023-12-21
