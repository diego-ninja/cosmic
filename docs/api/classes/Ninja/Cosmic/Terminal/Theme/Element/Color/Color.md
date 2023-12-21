***

# Color





* Full name: `\Ninja\Cosmic\Terminal\Theme\Element\Color\Color`
* Parent class: [`\Ninja\Cosmic\Terminal\Theme\Element\AbstractThemeElement`](../AbstractThemeElement.md)



## Properties


### name



```php
public string $name
```






***

### color



```php
public string $color
```






***

## Methods


### __construct



```php
public __construct(string $name, string $color): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |
| `$color` | **string** |  |





***

### fromArray



```php
public static fromArray(array $input): \Ninja\Cosmic\Terminal\Theme\Element\Color\Color
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$input` | **array** |  |





***

### load



```php
public load(\Symfony\Component\Console\Output\OutputInterface $output): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$output` | **\Symfony\Component\Console\Output\OutputInterface** |  |





***

### toArray



```php
public toArray(): array
```












***

### __toString



```php
public __toString(): string
```












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
