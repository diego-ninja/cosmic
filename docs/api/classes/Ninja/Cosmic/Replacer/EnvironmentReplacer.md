***

# EnvironmentReplacer





* Full name: `\Ninja\Cosmic\Replacer\EnvironmentReplacer`
* Parent class: [`\Ninja\Cosmic\Replacer\AbstractReplacer`](./AbstractReplacer.md)


## Constants

| Constant | Visibility | Type | Value |
|:---------|:-----------|:-----|:------|
|`REPLACER_PREFIX`|public| |&#039;env&#039;|

## Properties


### instance



```php
protected static ?\Ninja\Cosmic\Replacer\EnvironmentReplacer $instance
```



* This property is **static**.


***

## Methods


### getInstance



```php
public static getInstance(): \Ninja\Cosmic\Replacer\EnvironmentReplacer
```



* This method is **static**.








***

### getPlaceholderValue



```php
public getPlaceholderValue(string $placeholder): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$placeholder` | **string** |  |





***


## Inherited methods


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
