***

# AbstractReplacer





* Full name: `\Ninja\Cosmic\Replacer\AbstractReplacer`
* This class implements:
[`\Ninja\Cosmic\Replacer\ReplacerInterface`](./ReplacerInterface.md)
* This class is an **Abstract class**


## Constants

| Constant | Visibility | Type | Value |
|:---------|:-----------|:-----|:------|
|`REPLACER_PREFIX`|public| |null|


## Methods


### replace



```php
public replace(string $content): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$content` | **string** |  |





***

### __construct



```php
public __construct(): mixed
```











**Throws:**

- [`Exception`](../../../Exception.md)



***

### extractPlaceholders



```php
public extractPlaceholders(string $content): array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$content` | **string** |  |





***

### replacePlaceholder



```php
public replacePlaceholder(string $content, string $placeholder, mixed $value): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$content` | **string** |  |
| `$placeholder` | **string** |  |
| `$value` | **mixed** |  |





***

### getPrefix



```php
public getPrefix(): string
```












***

### getPlaceholderValue



```php
public getPlaceholderValue(string $placeholder): ?string
```




* This method is **abstract**.



**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$placeholder` | **string** |  |





***


***
> Automatically generated on 2023-12-21
