***

# ReplacerFactory





* Full name: `\Ninja\Cosmic\Replacer\ReplacerFactory`
* This class is marked as **final** and can't be subclassed
* This class is a **Final class**



## Properties


### replacers



```php
private static array $replacers
```



* This property is **static**.


***

## Methods


### getInstance



```php
public static getInstance(): self
```



* This method is **static**.








***

### registerReplacer



```php
public static registerReplacer(\Ninja\Cosmic\Replacer\ReplacerInterface $replacer): void
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$replacer` | **\Ninja\Cosmic\Replacer\ReplacerInterface** |  |





***

### getReplacers



```php
private getReplacers(): array
```












***

### replace



```php
public replace(string $content): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$content` | **string** |  |





***

### r



```php
public static r(string $content): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$content` | **string** |  |





***

### __construct



```php
private __construct(): mixed
```












***


***
> Automatically generated on 2023-12-21
