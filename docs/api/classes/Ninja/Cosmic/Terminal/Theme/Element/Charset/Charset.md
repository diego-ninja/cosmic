***

# Charset





* Full name: `\Ninja\Cosmic\Terminal\Theme\Element\Charset\Charset`
* Parent class: [`\Ninja\Cosmic\Terminal\Theme\Element\AbstractThemeElement`](../AbstractThemeElement.md)


## Constants

| Constant | Visibility | Type | Value |
|:---------|:-----------|:-----|:------|
|`CHAR_TOP`|public| |&quot;top&quot;|
|`CHAR_TOP_MID`|public| |&quot;top-mid&quot;|
|`CHAR_TOP_LEFT`|public| |&quot;top-left&quot;|
|`CHAR_TOP_RIGHT`|public| |&quot;top-right&quot;|
|`CHAR_BOTTOM`|public| |&quot;bottom&quot;|
|`CHAR_BOTTOM_MID`|public| |&quot;bottom-mid&quot;|
|`CHAR_BOTTOM_LEFT`|public| |&quot;bottom-left&quot;|
|`CHAR_BOTTOM_RIGHT`|public| |&quot;bottom-right&quot;|
|`CHAR_LEFT`|public| |&quot;left&quot;|
|`CHAR_LEFT_MID`|public| |&quot;left-mid&quot;|
|`CHAR_MID`|public| |&quot;mid&quot;|
|`CHAR_MID_MID`|public| |&quot;mid-mid&quot;|
|`CHAR_RIGHT`|public| |&quot;right&quot;|
|`CHAR_RIGHT_MID`|public| |&quot;right-mid&quot;|
|`CHAR_MIDDLE`|public| |&quot;middle&quot;|
|`CHARS`|private| |[self::CHAR_TOP, self::CHAR_TOP_MID, self::CHAR_TOP_LEFT, self::CHAR_TOP_RIGHT, self::CHAR_BOTTOM, self::CHAR_BOTTOM_MID, self::CHAR_BOTTOM_LEFT, self::CHAR_BOTTOM_RIGHT, self::CHAR_LEFT, self::CHAR_LEFT_MID, self::CHAR_MID, self::CHAR_MID_MID, self::CHAR_RIGHT, self::CHAR_RIGHT_MID, self::CHAR_MIDDLE]|

## Properties


### name



```php
public string $name
```






***

### chars



```php
public array $chars
```






***

## Methods


### __construct



```php
public __construct(string $name, array $chars): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |
| `$chars` | **array** |  |





***

### fromArray



```php
public static fromArray(array $input): \Ninja\Cosmic\Terminal\Theme\Element\Charset\Charset
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$input` | **array** |  |





***

### char



```php
public char(string $name): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |





***

### toArray



```php
public toArray(): array
```












***

### isComplete



```php
private static isComplete(array $chars): bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$chars` | **array** |  |





***


## Inherited methods


### toJson



```php
public toJson(): string
```











**Throws:**

- [`JsonException`](../../../../../../JsonException.md)



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


***
> Automatically generated on 2023-12-21
