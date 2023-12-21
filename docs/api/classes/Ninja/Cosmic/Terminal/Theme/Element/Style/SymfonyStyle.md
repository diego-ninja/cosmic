***

# SymfonyStyle





* Full name: `\Ninja\Cosmic\Terminal\Theme\Element\Style\SymfonyStyle`
* Parent class: [`\Ninja\Cosmic\Terminal\Theme\Element\Style\AbstractStyle`](./AbstractStyle.md)



## Properties


### name



```php
public string $name
```






***

### fg



```php
public ?string $fg
```






***

### bg



```php
public ?string $bg
```






***

### options



```php
public ?array $options
```






***

## Methods


### __construct



```php
public __construct(string $name, ?string $fg, ?string $bg, ?array $options): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |
| `$fg` | **?string** |  |
| `$bg` | **?string** |  |
| `$options` | **?array** |  |





***

### fromArray



```php
public static fromArray(array $input): \Ninja\Cosmic\Terminal\Theme\Element\Style\SymfonyStyle
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$input` | **array** |  |





***

### __toString



```php
public __toString(): string
```












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


## Inherited methods


### fromArray



```php
public static fromArray(array $input): self
```



* This method is **static**.
* This method is **abstract**.



**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$input` | **array** |  |





***

### load



```php
public load(\Symfony\Component\Console\Output\OutputInterface $output): void
```




* This method is **abstract**.



**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$output` | **\Symfony\Component\Console\Output\OutputInterface** |  |





***

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
