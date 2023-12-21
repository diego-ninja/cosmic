***

# Asset

Class Asset



* Full name: `\Ninja\Cosmic\Application\Publisher\Asset\Asset`


## Constants

| Constant | Visibility | Type | Value |
|:---------|:-----------|:-----|:------|
|`STATE_UPLOADED`|public| |&#039;uploaded&#039;|
|`STATE_VERIFIED`|public| |&#039;verified&#039;|
|`STATE_PUBLISHED`|public| |&#039;published&#039;|
|`STATE_UNSIGNED`|public| |&#039;unsigned&#039;|

## Properties


### state



```php
private string $state
```






***

### createdAt



```php
private \Carbon\CarbonImmutable $createdAt
```






***

### updatedAt



```php
private ?\Carbon\CarbonImmutable $updatedAt
```






***

### size



```php
private ?int $size
```






***

### signature



```php
private ?\Ninja\Cosmic\Application\Publisher\Asset\Signature $signature
```






***

### contentType



```php
private ?string $contentType
```






***

### name



```php
public string $name
```






***

### path



```php
public ?string $path
```






***

### url



```php
public ?string $url
```






***

## Methods


### __construct



```php
public __construct(string $name, ?string $path = null, ?string $url = null): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |
| `$path` | **?string** |  |
| `$url` | **?string** |  |





***

### isVerified



```php
public isVerified(): bool
```












***

### setCreatedAt



```php
public setCreatedAt(\Carbon\CarbonImmutable $createdAt): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$createdAt` | **\Carbon\CarbonImmutable** |  |





***

### setUpdatedAt



```php
public setUpdatedAt(\Carbon\CarbonImmutable $updatedAt): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$updatedAt` | **\Carbon\CarbonImmutable** |  |





***

### fromArray



```php
public static fromArray(array $data): self
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **array** |  |





***

### setContentType



```php
public setContentType(string $contentType): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contentType` | **string** |  |





***

### setSize



```php
public setSize(int $size): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$size` | **int** |  |





***

### setState



```php
public setState(string $state): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$state` | **string** |  |





***

### getCreatedAt



```php
public getCreatedAt(): \Carbon\CarbonImmutable
```












***

### getUpdatedAt



```php
public getUpdatedAt(): ?\Carbon\CarbonImmutable
```












***

### getState



```php
public getState(): string
```












***

### getSize



```php
public getSize(): int
```












***

### getContentType



```php
public getContentType(): string
```












***

### fromJson



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



```php
public __toString(): string
```












***

### setSignature



```php
public setSignature(\Ninja\Cosmic\Application\Publisher\Asset\Signature $signature): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$signature` | **\Ninja\Cosmic\Application\Publisher\Asset\Signature** |  |





***

### getSignature



```php
public getSignature(): ?\Ninja\Cosmic\Application\Publisher\Asset\Signature
```












***


***
> Automatically generated on 2023-12-21
