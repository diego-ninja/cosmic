***

# Spinner





* Full name: `\Ninja\Cosmic\Terminal\Theme\Element\Spinner\Spinner`
* Parent class: [`\Ninja\Cosmic\Terminal\Theme\Element\AbstractThemeElement`](../AbstractThemeElement.md)



## Properties


### name



```php
public string $name
```






***

### frames



```php
public array $frames
```






***

### interval



```php
public int $interval
```






***

## Methods


### __construct



```php
public __construct(string $name, array $frames, int $interval): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |
| `$frames` | **array** |  |
| `$interval` | **int** |  |





***

### fromArray



```php
public static fromArray(array $input): \Ninja\Cosmic\Terminal\Theme\Element\Spinner\Spinner
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$input` | **array** |  |





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
