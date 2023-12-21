***

# Uid





* Full name: `\Ninja\Cosmic\Crypt\Uid`
* This class is marked as **final** and can't be subclassed
* This class is a **Final class**



## Properties


### name



```php
public string $name
```






***

### email



```php
public string $email
```






***

### trustLevel



```php
public string $trustLevel
```






***

## Methods


### __construct



```php
public __construct(string $name, string $email, string $trustLevel): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |
| `$email` | **string** |  |
| `$trustLevel` | **string** |  |





***

### fromString



```php
public static fromString(string $string): self
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$string` | **string** |  |





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

### __toString



```php
public __toString(): string
```












***


***
> Automatically generated on 2023-12-21
