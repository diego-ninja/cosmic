***

# CommandReplacer





* Full name: `\Ninja\Cosmic\Replacer\CommandReplacer`
* Parent class: [`\Ninja\Cosmic\Replacer\AbstractReplacer`](./AbstractReplacer.md)
* This class is marked as **final** and can't be subclassed
* This class is a **Final class**


## Constants

| Constant | Visibility | Type | Value |
|:---------|:-----------|:-----|:------|
|`REPLACER_PREFIX`|public| |&#039;command&#039;|

## Properties


### instance



```php
protected static ?\Ninja\Cosmic\Replacer\CommandReplacer $instance
```



* This property is **static**.


***

### command



```php
private \Ninja\Cosmic\Command\Command $command
```






***

## Methods


### __construct



```php
public __construct(\Ninja\Cosmic\Command\Command $command): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$command` | **\Ninja\Cosmic\Command\Command** |  |





***

### forCommand



```php
public static forCommand(\Ninja\Cosmic\Command\Command $command): \Ninja\Cosmic\Replacer\ReplacerInterface
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$command` | **\Ninja\Cosmic\Command\Command** |  |




**Throws:**

- [`Exception`](../../../Exception.md)



***

### getPlaceholderValue



```php
public getPlaceholderValue(string $placeholder): ?string
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
