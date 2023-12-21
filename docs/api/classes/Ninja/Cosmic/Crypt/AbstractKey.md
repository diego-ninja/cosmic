***

# AbstractKey

Class AbstractKey



* Full name: `\Ninja\Cosmic\Crypt\AbstractKey`
* This class implements:
[`\Ninja\Cosmic\Crypt\KeyInterface`](./KeyInterface.md), [`\Ninja\Cosmic\Serializer\SerializableInterface`](../Serializer/SerializableInterface.md), [`\Ninja\Cosmic\Terminal\Table\TableableInterface`](../Terminal/Table/TableableInterface.md)
* This class is an **Abstract class**



## Properties


### subKeys



```php
protected \Ninja\Cosmic\Crypt\KeyCollection $subKeys
```






***

### id



```php
public string $id
```






***

### method



```php
public string $method
```






***

### usage



```php
public string $usage
```






***

### createdAt



```php
public \Carbon\CarbonImmutable $createdAt
```






***

### expiresAt



```php
public ?\Carbon\CarbonImmutable $expiresAt
```






***

### uid



```php
public ?\Ninja\Cosmic\Crypt\Uid $uid
```






***

### fingerprint



```php
public ?string $fingerprint
```






***

## Methods


### __construct

AbstractKey constructor.

```php
public __construct(string $id, string $method, string $usage, \Carbon\CarbonImmutable $createdAt, \Carbon\CarbonImmutable|null $expiresAt = null, \Ninja\Cosmic\Crypt\Uid|null $uid = null, string|null $fingerprint = null): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$id` | **string** |  |
| `$method` | **string** |  |
| `$usage` | **string** |  |
| `$createdAt` | **\Carbon\CarbonImmutable** |  |
| `$expiresAt` | **\Carbon\CarbonImmutable&#124;null** |  |
| `$uid` | **\Ninja\Cosmic\Crypt\Uid&#124;null** |  |
| `$fingerprint` | **string&#124;null** |  |





***

### isAbleTo

Check if the key is able to perform a specific usage.

```php
public isAbleTo(string $usage): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$usage` | **string** |  |





***

### __toString

Get a string representation of the key.

```php
public __toString(): string
```












***

### fromArray

Create an instance of AbstractKey from an array of data.

```php
public static fromArray(array $data): static
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **array** |  |





***

### fromString

Create an instance of AbstractKey from a string.

```php
public static fromString(string $string): static
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$string` | **string** |  |





***

### addSubKey

Add a sub key to the key.

```php
public addSubKey(\Ninja\Cosmic\Crypt\KeyInterface $key): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$key` | **\Ninja\Cosmic\Crypt\KeyInterface** |  |





***

### isAbleToSign

Check if the key is able to sign.

```php
public isAbleToSign(): bool
```












***

### getSignatureFilePath

Get the signature file path for a given file path.

```php
public static getSignatureFilePath(string $file_path): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$file_path` | **string** |  |





***

### render

Render the key information to the output.

```php
public render(\Symfony\Component\Console\Output\OutputInterface $output): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$output` | **\Symfony\Component\Console\Output\OutputInterface** |  |




**Throws:**

- [`MissingInterfaceException`](../Exception/MissingInterfaceException.md)

- [`UnexpectedValueException`](../Exception/UnexpectedValueException.md)



***


## Inherited methods


### toJson



```php
public toJson(): string
```











**Throws:**

- [`JsonException`](../../../JsonException.md)



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

- [`MissingInterfaceException`](../Exception/MissingInterfaceException.md)

- [`UnexpectedValueException`](../Exception/UnexpectedValueException.md)



***

### asTable



```php
public asTable(): \Ninja\Cosmic\Terminal\Table\Table
```











**Throws:**

- [`MissingInterfaceException`](../Exception/MissingInterfaceException.md)

- [`UnexpectedValueException`](../Exception/UnexpectedValueException.md)



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
